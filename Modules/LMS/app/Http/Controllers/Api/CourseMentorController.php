<?php

namespace Modules\LMS\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\LMS\Models\Course;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseMentorController extends Controller
{
    /**
     * GET /api/courses/{slug}/mentors
     * List mentor course (public)
     */
    public function index(string $slug): JsonResponse
    {
        try {
            $course  = Course::where('slug', $slug)->firstOrFail();
            $mentors = $course->mentors()
                ->select('users.id', 'users.name', 'users.email')
                // ->wherePivot('is_active', true)
                ->get()
                ->map(fn(User $user) => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'is_active' => $user->pivot->is_active,
                ]);
    
            return response()->json([
                'message' => 'Success',
                'data'    => $mentors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data mentor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
 
    /**
     * POST /api/courses/{slug}/mentors
     * Assign mentor ke course (admin Pusat)
     */
    public function store(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);
 
        try {
            $course = Course::where('slug', $slug)->firstOrFail();
 
            // Cek sudah jadi mentor di course ini
            $exists = $course->mentors()->where('user_id', $request->user_id)->exists();
            if ($exists) {
                return response()->json([
                    'message' => 'User sudah menjadi mentor di course ini',
                ], 422);
            }
    
            $course->mentors()->attach($request->user_id, [
                'is_active' => true,
            ]);
    
            return response()->json([
                'message' => 'Mentor berhasil ditambahkan',
            ], 201);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'Gagal menambahkan mentor',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
 
    /**
     * PATCH /api/courses/{slug}/mentors/{userId}/toggle
     * Toggle is_active mentor (admin)
     */
    public function toggleMentorActivation(string $slug, string $userId): JsonResponse
    {
        try {
            $course = Course::where('slug', $slug)->firstOrFail();
            $mentor = $course->mentors()
            ->wherePivot('user_id', $userId)
            ->firstOrFail();

            $course->mentors()->updateExistingPivot($userId, [
                'is_active' => ! $mentor->pivot->is_active,
            ]);

            return response()->json([
                'message' => 'Status mentor berhasil diubah',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah status mentor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /api/courses/{slug}/mentors/{userId}
     * Hapus mentor dari course (admin)
     */
    public function destroy(string $slug, string $userId): JsonResponse
    {
        try {
            $course = Course::where('slug', $slug)->firstOrFail();
            $course->mentors()->detach($userId);

            return response()->json([
                'message' => 'Mentor berhasil dihapus dari course',
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'Gagal menghapus mentor',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
