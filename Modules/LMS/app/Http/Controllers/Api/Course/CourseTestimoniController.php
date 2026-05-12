<?php

namespace Modules\LMS\Http\Controllers\Api\Course;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Http\Requests\Api\StoreCourseTestimoniRequest;
use Modules\LMS\Http\Requests\Api\UpdateCourseTestimoniRequest;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseTestimoni;
use Modules\LMS\Services\Api\CourseTestimoniService;
use Modules\LMS\Transformers\Api\CourseTestimoniResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseTestimoniController extends Controller
{
    public function __construct(
        private CourseTestimoniService $service
    ) {}

    /**
     *  Menampilkan semua testimoni dari course
     * 
     * Slug course digunakan untuk mencari course yang akan menampilkan testimoninya
     * 
     * @unauthenticated
     * @role:admin-pusat    
     */
    public function index(Request $request,string $slug): JsonResponse
    {
        try {
            $row_per_page = $request->input('row_per_page', 20);
            $testimonis   = $this->service->getTestimonis(
                slug: $slug,
                perPage: $row_per_page
            );

            return ResponseHelper::success(
                status: true,
                message: 'Testimonis retrieved successfully',
                result: PaginateResource::make($testimonis, CourseTestimoniResource::class),
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
     * Menambahkan testimoni ke course
     *
     * @tags Testimonis
     * @authenticated
     * @urlParam slug string required Slug course. Example: laravel-dasar
     */
    public function store(StoreCourseTestimoniRequest $request, string $slug): JsonResponse
    {
        try {
            $result = $this->service->store($request->validated(), $slug);

            if (! $result['success']) {
                return ResponseHelper::error(
                    message: $result['message'],
                    statusCode: $result['code']
                );
            }

            return ResponseHelper::success(
                status: true,
                message: $result['message'],
                result: new CourseTestimoniResource($result['testimoni']),
                statusCode: 201
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Update testimoni
     *
     * @tags Testimonis
     * @authenticated
     * @urlParam testimoni string required ID testimoni. Example: uuid-xxx
     */
    public function update(UpdateCourseTestimoniRequest $request, CourseTestimoni $testimoni): JsonResponse
    {
        try {
            $result = $this->service->update($request->validated(), $testimoni);

            if (! $result['success']) {
                return ResponseHelper::error(
                    message: $result['message'],
                    statusCode: $result['code']
                );
            }

            return ResponseHelper::success(
                status: true,
                message: $result['message'],
                result: new CourseTestimoniResource($result['testimoni']),
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
     * Menghapus testimoni
     *
     * @tags Testimonis
     * @authenticated
     * @urlParam testimoni string required ID testimoni. Example: uuid-xxx
     */
    public function destroy(CourseTestimoni $testimoni): JsonResponse
    {
        try {
            $result = $this->service->destroy($testimoni);

            if (! $result['success']) {
                return ResponseHelper::error(
                    message: $result['message'],
                    statusCode: $result['code']
                );
            }

            return ResponseHelper::success(
                status: true,
                message: $result['message'],
                result: null,
                statusCode: 200
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: 'Gagal menghapus testimoni',
                statusCode: 500
            );
        }
    }
}
