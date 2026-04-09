<?php

namespace Modules\LMS\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\LMS\Http\Requests\Api\StoreCourseRequest;
use Modules\LMS\Http\Requests\Api\UpdateCourseRequest;
use Modules\LMS\Models\Course;
use Modules\LMS\Transformers\Api\CourseResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseController extends Controller
{
    /**
     * GET /api/courses
     * List semua course, bisa filter by category
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $row_per_page = $request->input('row_per_page', 5);
            $search = $request->input('search');
            $category_id = $request->input('category_id');

            $courses = Course::with(['category', 'benefits', 'testimonis'])
                ->withCount(['benefits', 'students'])
                ->when($category_id, fn($q) => $q->where('category_id', $category_id))
                ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->latest()
                ->paginate($row_per_page);
                
            return ResponseHelper::success(
                status: true,
                message: 'Users retrieved successfully',
                result: PaginateResource::make($courses, CourseResource::class),
                statusCode: 200
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * GET /api/courses/{slug}
     * Detail course beserta benefits dan testimonis
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $course = Course::with([
            'category',
            'user',
            'benefits',
            'testimonis',
        ])
        ->withCount('students')
        ->where('slug', $slug)
        ->firstOrFail();

        return ResponseHelper::success(
            status: true,
            message: 'Course retrieved successfully',
            result: new CourseResource($course),
            statusCode: 200
        );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * POST /api/courses
     */
    public function store(StoreCourseRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('courses/thumbnails', 'public');
        }

        $course = Course::create($data);

        return ResponseHelper::success(
            status: true,
            message: 'Course created successfully',
            result: new CourseResource($course->load('category', 'user')),
            statusCode: 201
        );
    }

    /**
     * PUT /api/courses/{slug}
     */
    public function update(UpdateCourseRequest $request, string $slug): JsonResponse
    {
        try {
            $course = Course::where('slug', $slug)->firstOrFail();
            $data   = $request->validated();

            if ($request->hasFile('thumbnail')) {
                // Hapus thumbnail lama jika ada
                if ($course->thumbnail) {
                    Storage::disk('public')->delete($course->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')
                    ->store('courses/thumbnails', 'public');
            }

            // Re-generate slug jika name berubah
            // if (isset($data['name'])) {
            //     $data['slug'] = Str::slug($data['name']);
            // }

            $course->update($data);

            return ResponseHelper::success(
                status: true,
                message: 'Course updated successfully',
                result: new CourseResource($course->fresh(['category', 'user'])),
                statusCode: 200
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * DELETE /api/courses/{slug}
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $course = Course::where('slug', $slug)->first();

            if (!$course) {
                return ResponseHelper::error(
                    message: 'Course not found',
                    statusCode: 404
                );
            }

        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return ResponseHelper::success(
            status: true,
            message: 'Course deleted successfully',
            result: null,
            statusCode: 200
        );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }
}
