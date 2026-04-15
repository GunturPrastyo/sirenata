<?php

namespace Modules\LMS\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\LMS\Models\Course;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseStudentController extends Controller
{
    /**
     * List semua student yang enroll / terdaftar (admin only)
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function index(string $slug): JsonResponse
    {
        $course   = Course::where('slug', $slug)->firstOrFail();
        $students = $course->students()
            ->select('users.id', 'users.name', 'users.email')
            ->paginate(20);

        $data = $students->map(function (User $user) {
            return [
                'id'                   => $user->id,
                'name'                 => $user->name,
                'email'                => $user->email,
                'status'               => $user->pivot->status,
                'progress'             => $user->pivot->progress,
                'completed_at'         => $user->pivot->completed_at,
                'certificate_code'     => $user->pivot->certificate_code,
                'certificate_issued_at'=> $user->pivot->certificate_issued_at,
                'enrolled_at'          => $user->pivot->created_at,
            ];
        });

        return response()->json([
            'message' => 'Success',
            'data'    => $data,
            'meta'    => [
                'current_page' => $students->currentPage(),
                'last_page'    => $students->lastPage(),
                'total'        => $students->total(),
            ],
        ]);
    }
 
    /**
     *  Menambahkan user ke course 
     * 
     * menambahkan user menjadi terdaftar di course yang sedang user login berdasarkan user_id user login
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function enroll(string $slug): JsonResponse
    {
       try {
         $course = Course::where('slug', $slug)->firstOrFail();
        $userId = auth()->id(); 
        // Cek kalau sudah enroll
        $alreadyEnrolled = $course->students()->where('user_id', $userId)->exists();
        if ($alreadyEnrolled) {
            return response()->json([
                'message' => 'Kamu sudah terdaftar di course ini',
            ], 422);
        }

        $course->students()->attach($userId, [
            'status'   => 'enrolled',
            'progress' => 0,
        ]);

        return response()->json([
            'message' => 'Berhasil enroll ke course',
        ], 201);
       } catch (\Exception $th) {
        return response()->json([
            'message' => 'Gagal enroll ke course',
            'error' => $th->getMessage(),
        ], 500);
       }
    }

    /**
     *  Update status student di course
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function updateStatus(Request $request, string $slug, string $userId): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:enrolled,in_progress,completed'],
        ]);

        try {
            $course = Course::where('slug', $slug)->firstOrFail();

            $student = $course->students()
                ->wherePivot('user_id', $userId)
                ->first();

            if (!$student) {
                return response()->json([
                    'message' => 'Student tidak ditemukan di course ini',
                ], 404);
            }

            // Tidak bisa diubah kalau sudah completed dan progress 100
            if ($student->pivot->status === 'completed' && $student->pivot->progress === 100) {
                return response()->json([
                    'message' => 'Status student yang sudah completed tidak bisa diubah',
                ], 422);
            }

            $pivotData = ['status' => $request->status];

            // Auto-fill completed_at kalau status di-set completed
            if ($request->status === 'completed') {
                $pivotData['completed_at'] = now();
                $pivotData['progress']     = 100;
            }

            $course->students()->updateExistingPivot($userId, $pivotData);

            return response()->json([
                'message' => 'Status student berhasil diupdate',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal update status student',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * MyCourse 
     * 
     * 
     * Course yang diikuti oleh user yang login
     * 
     * @authenticated
     * @role:user
     */
    public function myCourses(): JsonResponse
    {
        try {
        $user    = auth()->user();
            $courses = $user->enrolledCourses()  // relasi balik di User model
            ->with(['category', 'benefits', 'testimonis'])
            ->paginate(12);
 
        $data = $courses->map(function (Course $course) {
            return [
                'id'            => $course->id,
                'name'          => $course->name,
                'slug'          => $course->slug,
                'thumbnail_url' => $course->thumbnail_url,
                'category'      => $course->category?->name,
                'status'        => $course->pivot->status,
                'progress'      => $course->pivot->progress,
                'created_at'    => $course->pivot->created_at,
                'benefits'      => $course->benefits->pluck('name'),
                'testimonis'    => $course->testimonis,
            ];
        });

        return response()->json([
            'message' => 'Success',
            'auth' => [
                'user_id' => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
            ],
            'data'    => $data,
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal update status student',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     *  Unenroll dari course
     * 
     * @authenticated
     * 
     */
    public function unenroll(string $slug): JsonResponse
    {
       try {
            $course  = Course::where('slug', $slug)->firstOrFail();
            $userId  = auth()->id();
            $student = $course->students()
                ->wherePivot('user_id', $userId)
                ->first();

            if (!$student) {
                return response()->json([
                    'message' => 'Kamu tidak terdaftar di course ini',
                ], 404);
            }

            if ($student->pivot->status === 'completed') {
                return response()->json([
                    'message' => 'Course yang sudah diselesaikan tidak bisa di-unenroll',
                ], 422);
            }

            $course->students()->detach($userId);

            return response()->json([
                'message' => 'Berhasil keluar dari course',
            ]);
       } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal keluar dari course',
                'error' => $e->getMessage(),
            ], 500);
       }
    }
}
