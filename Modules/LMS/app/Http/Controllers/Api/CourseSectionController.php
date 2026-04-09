<?php

namespace Modules\LMS\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Http\Requests\Api\StoreCourseSectionRequest;
use Modules\LMS\Http\Requests\Api\UpdateCourseSectionRequest;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseSection;
use Modules\LMS\Transformers\Api\CourseSectionResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseSectionController extends Controller
{
    /**
     * GET /api/v1/courses/{slug}/sections
     * List semua section beserta contents (public)
     */
    public function index(string $slug): JsonResponse
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        $sections = $course->sections()
            ->withCount('contents')
            ->with('contents')
            ->orderBy('position')
            ->get();

        return response()->json([
            'message' => 'Success',
            'data'    => CourseSectionResource::collection($sections),
        ]);
    }

    /**
     * POST /api/v1/courses/{slug}/sections
     * Buat section baru (admin)
     */
    public function store(StoreCourseSectionRequest $request, string $slug): JsonResponse
    {
        $course = Course::where('slug', $slug)->firstOrFail();
 
        // Auto-set position ke urutan terakhir kalau tidak diisi
        $position = $request->position
            ?? $course->sections()->max('position') + 1;
 
        $section = $course->sections()->create([
            ...$request->validated(),
            'position' => $position,
        ]);
 
        return response()->json([
            'message' => 'Section berhasil dibuat',
            'data'    => new CourseSectionResource($section),
        ], 201);
    }
 
    /**
     * PUT /api/v1/sections/{section}
     * Update section (admin)
     */
    public function update(UpdateCourseSectionRequest $request, CourseSection $section): JsonResponse
    {
        $section->update($request->validated());
 
        return response()->json([
            'message' => 'Section berhasil diupdate',
            'data'    => new CourseSectionResource($section->fresh('contents')),
        ]);
    }
 
    /**
     * DELETE /api/v1/sections/{section}
     * Hapus section beserta semua contents-nya (admin)
     */
    public function destroy(CourseSection $section): JsonResponse
    {
        // Contents terhapus otomatis karena cascadeOnDelete di migration
        $section->delete();
 
        return response()->json([
            'message' => 'Section berhasil dihapus',
        ]);
    }
 
    /**
     * PATCH /api/v1/courses/{slug}/sections/reorder
     * Ubah urutan section (admin)
     */
    public function reorder(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'sections'          => ['required', 'array'],
            'sections.*.id'     => ['required', 'uuid', 'exists:course_sections,id'],
            'sections.*.position' => ['required', 'integer', 'min:0'],
        ]);
 
        $course = Course::where('slug', $slug)->firstOrFail();
 
        foreach ($request->sections as $item) {
            $course->sections()
                ->where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }
 
        return response()->json([
            'message' => 'Urutan section berhasil diupdate',
        ]);
    }
}
