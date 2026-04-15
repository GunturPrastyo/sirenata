<?php

namespace Modules\LMS\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Http\Requests\Api\StoreCourseTestimoniRequest;
use Modules\LMS\Http\Requests\Api\UpdateCourseTestimoniRequest;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseTestimoni;
use Modules\LMS\Transformers\Api\CourseTestimoniResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseTestimoniController extends Controller
{
    /**
     * GET /api/courses/{slug}/testimonis
     */
    public function index(string $slug): JsonResponse
    {
        $course     = Course::where('slug', $slug)->firstOrFail();
        $testimonis = $course->testimonis()->latest()->paginate(10);

        return response()->json([
            'message' => 'Success',
            'data'    => CourseTestimoniResource::collection($testimonis),
        ]);
    } 

    /**
     * POST /api/courses/{slug}/testimonis
     */
    public function store(StoreCourseTestimoniRequest $request, string $slug): JsonResponse
    {
        $userId = Auth::user()->id;
        $course    = Course::where('slug', $slug)->firstOrFail();
        $validated = $request->validated();
        $validated['user_id'] = $userId;
        $validated['name'] = Auth::user()->profile->full_name ?? Auth::user()->name;
        // $testimoni = $course->testimonis()->create($validated);

        
        $isEnrolled = $course->students()
            ->wherePivot('user_id', $userId)
            ->wherePivotIn('status', ['enrolled', 'in_progress', 'completed'])
            // ->wherePivot('status', 'completed')
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'message' => 'Kamu harus terdaftar di course ini untuk memberikan testimoni',
            ], 403);
        }

        // Ketika coursenya completed baru bisa kasih testimoni
        // if (!$isEnrolled) {
        //     return response()->json([
        //         'message' => 'Kamu harus menyelesaikan course ini untuk memberikan testimoni',
        //     ], 403);
        // }

        // Cek sudah pernah testimoni belum
        $alreadyReviewed = $course->testimonis()
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'message' => 'Kamu sudah memberikan testimoni untuk course ini',
            ], 422);
        }

        $testimoni = $course->testimonis()->create($validated); 
        return response()->json([
            'message' => 'Testimoni berhasil ditambahkan',
            'data'    => new CourseTestimoniResource($testimoni),
        ], 201);
    }

    /**
     * PUT /api/testimonis/{testimoni}
     */
    public function update(UpdateCourseTestimoniRequest $request, CourseTestimoni $testimoni): JsonResponse
    {
        // Hanya pemilik testimoni yang bisa update
        abort_if(
            $testimoni->user_id !== null && $testimoni->user_id !== Auth::user()->id,
            403,
            'Akses ditolak'
        );

        $testimoni->update($request->validated()); 
        return response()->json([
            'message' => 'Testimoni berhasil diupdate',
            'data'    => new CourseTestimoniResource($testimoni),
        ]);
    }

    /**
     * DELETE /api/testimonis/{testimoni}
     */
    public function destroy(CourseTestimoni $testimoni): JsonResponse
    {
        $testimoni->delete(); 
        return response()->json([
            'message' => 'Testimoni berhasil dihapus',
        ]);
    }
}
