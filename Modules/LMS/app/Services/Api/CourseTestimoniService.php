<?php

namespace Modules\LMS\Services\Api;

use Illuminate\Support\Facades\Auth;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseTestimoni;
use Modules\LMS\Transformers\Api\CourseTestimoniResource;

class CourseTestimoniService
{
    /**
     * List semua testimoni dari course
     */
    public function getTestimonis(string $slug, int $perPage = 10)
    {
        $course     = Course::where('slug', $slug)->firstOrFail();
        $testimonis = $course->testimonis()->latest()->paginate($perPage);
 
        return $testimonis;
    }

    /**
     * Tambah testimoni ke course
     */
    public function store(array $validated, string $slug): array
    {
        $user   = Auth::user();
        $userId = $user->id;
        $course = Course::where('slug', $slug)->firstOrFail();
 
        // Cek apakah user sudah enroll di course ini
        $isEnrolled = $course->students()
            ->wherePivot('user_id', $userId)
            ->wherePivotIn('status', ['enrolled', 'in_progress', 'completed'])
            ->exists();
 
        if (! $isEnrolled) {
            return [
                'success' => false,
                'code'    => 403,
                'message' => 'Kamu harus terdaftar di course ini untuk memberikan testimoni',
            ];
        }
 
        // Cek sudah pernah testimoni belum
        $alreadyReviewed = $course->testimonis()
            ->where('user_id', $userId)
            ->exists();
 
        if ($alreadyReviewed) {
            return [
                'success' => false,
                'code'    => 422,
                'message' => 'Kamu sudah memberikan testimoni untuk course ini',
            ];
        }
 
        $testimoni = $course->testimonis()->create([
            ...$validated,
            'user_id' => $userId,
            'name'    => $user->profile->full_name ?? $user->name,
        ]);
 
        return [
            'success'   => true,
            'code'      => 201,
            'message'   => 'Testimoni berhasil ditambahkan',
            'testimoni' => $testimoni,
        ];
    }


    /**
     * Update testimoni — hanya pemilik yang bisa update
     */
    public function update(array $validated, CourseTestimoni $testimoni): array
    {
        $user = Auth::user();
 
        // Hanya pemilik testimoni yang bisa update
        if ($testimoni->user_id !== null && $testimoni->user_id !== $user->id) {
            return [
                'success' => false,
                'code'    => 403,
                'message' => 'Akses ditolak',
            ];
        }
 
        $testimoni->update([
            ...$validated,
            'user_id' => $user->id,
            'name'    => $user->profile->full_name ?? $user->name,
        ]);
 
        return [
            'success'   => true,
            'code'      => 200,
            'message'   => 'Testimoni berhasil diupdate',
            'testimoni' => $testimoni->fresh(),
        ];
    }

        /**
     * Hapus testimoni — hanya pemilik yang bisa hapus
     */
    public function destroy(CourseTestimoni $testimoni): array
    {
        $user = Auth::user();
 
        if ($testimoni->user_id !== null && $testimoni->user_id !== $user->id) {
            return [
                'success' => false,
                'code'    => 403,
                'message' => 'Akses ditolak',
            ];
        }
 
        $testimoni->delete();
 
        return [
            'success' => true,
            'code'    => 200,
            'message' => 'Testimoni berhasil dihapus',
        ];
    }
}
