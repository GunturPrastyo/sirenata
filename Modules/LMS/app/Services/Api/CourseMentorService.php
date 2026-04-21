<?php

namespace Modules\LMS\Services\Api;

use App\Models\User;
use Modules\LMS\Models\Course;
use Illuminate\Validation\ValidationException;

class CourseMentorService
{
    public function getMentorsBySlug(string $slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        $mentors = $course->mentors()
            ->select('users.id', 'users.name', 'users.email')
            ->get();

        return $mentors;
    }

    public function addMentor(string $slug, string $userId): void
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        // cek sudah ada atau belum
        $exists = $course->mentors()
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            throw new \Exception('User sudah menjadi mentor di course ini', 422);
        }

        $course->mentors()->attach($userId, [
            'is_active' => true,
        ]);
    }

    public function toggleMentor(string $slug, string $userId): void
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        $mentor = $course->mentors()
            ->where('users.id', $userId)
            ->first();

        if (!$mentor) {
            throw ValidationException::withMessages([
                'user_id' => ['User bukan mentor di course ini']
            ]);
        }

        $course->mentors()->updateExistingPivot($userId, [
            'is_active' => ! $mentor->pivot->is_active,
        ]);
    }

    public function removeMentor(string $slug, string $userId): void
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        // cek apakah user memang mentor di course ini
        $exists = $course->mentors()
            ->where('users.id', $userId)
            ->exists();

        if (!$exists) {
            throw ValidationException::withMessages([
                'user_id' => ['User bukan mentor di course ini']
            ]);
        }

        $course->mentors()->detach($userId);
    }
}
