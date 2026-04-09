<?php

namespace Modules\LMS\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Http\Requests\Api\StoreCourseBenefitRequest;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseBenefits;
use Modules\LMS\Transformers\Api\CourseBenefitResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseBenefitController extends Controller
{
    /**
     * GET /api/courses/{slug}/benefits
     */
    public function index(string $slug)
    {
        try {
            $course   = Course::where('slug', $slug)->firstOrFail();
            $benefits = $course->benefits;

            return response()->json([
                'message' => 'Success',
                'data'    => CourseBenefitResource::collection($benefits),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/courses/{slug}/benefits
     */
    public function store(StoreCourseBenefitRequest $request, string $slug): JsonResponse
    {
        $course  = Course::where('slug', $slug)->firstOrFail();
        $benefit = $course->benefits()->create($request->validated());

        return response()->json([
            'message' => 'Benefit berhasil ditambahkan',
            'data'    => new CourseBenefitResource($benefit),
        ], 201);
    }

    /**
     * PUT /api/courses/{slug}/benefits
     */
    public function update(StoreCourseBenefitRequest $request, CourseBenefits $benefit): JsonResponse
    {
        $benefit->update($request->validated());

        return response()->json([
            'message' => 'Benefit berhasil diupdate',
            'data'    => new CourseBenefitResource($benefit),
        ], 201);
    }

    /**
     * DELETE /api/courses/{slug}/benefits/{benefit}
     */
    public function destroy(CourseBenefits $benefit): JsonResponse
    {
        // Pastikan benefit milik course yang benar
        $course = $benefit->course->id;
        abort_if($benefit->course_id !== $course, 404, 'Benefit tidak ditemukan');
        $benefit->delete();

        return response()->json([
            'message' => 'Benefit berhasil dihapus',
        ]);
    }
}
