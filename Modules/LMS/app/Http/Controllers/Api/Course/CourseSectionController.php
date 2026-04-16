<?php

namespace Modules\LMS\Http\Controllers\Api\Course;

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
     * List section berdasarkan slug course
     * 
     * List section berdasarkan slug course
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function index(string $slug): JsonResponse
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        $sections = $course->sections()
            ->withCount(['contents'])
            ->with(['contents'])
            ->orderBy('position')
            ->get();

        return response()->json([
            'message' => 'Success',
            'data'    => CourseSectionResource::collection($sections),
        ]);
    }

    /**
     * Tambah section
     * 
     * Tambah section berdasarkan slug course
     * 
     * @body CourseSection
     * @authenticated
     * @role:admin-pusat
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
     * Update section
     * 
     * Update section berdasarkan id CourseSection
     * 
     * @body CourseSection
     * @authenticated
     * @role:admin-pusat
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
     * Hapus section
     * 
     * Hapus section berdasarkan id CourseSection
     * 
     * @authenticated
     * @role:admin-pusat
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
     * Ubah urutan Course section
     * 
     * Ubah urutan Course section berdasarkan slug course
     * 
     * @body CourseSection
     * @authenticated
     * @role:admin-pusat
     */
    public function reorder(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'sections'          => ['required', 'array'],
            'sections.*.id'     => ['required', 'uuid', 'exists:course_sections,id'],
            'sections.*.position' => ['required', 'integer', 'min:1'],
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
