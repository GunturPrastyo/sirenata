<?php

namespace Modules\LMS\Http\Controllers\Api\Course;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\LMS\Models\Course;
use Modules\LMS\Services\Api\CourseMentorService;
use Modules\LMS\Transformers\Api\CourseMentorResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseMentorController extends Controller
{

    public function __construct(
        private readonly CourseMentorService $courseMentorService
    ) {}

    /**
     * List mentor course
     * 
     * List mentor berdasarkan slug course
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function index(string $slug): JsonResponse
    {
        try {
            $mentors = $this->courseMentorService->getMentorsBySlug(
                slug: $slug 
            );
           return ResponseHelper::success(
                status: true,
                message: 'Success',
                result: CourseMentorResource::collection($mentors),
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
     * Tambah mentor
     * 
     * Tambah mentor berdasarkan slug course
     * 
     * @body User
     * @authenticated
     * @role:admin-pusat
     */
    public function store(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);
 
        try {
            $this->courseMentorService->addMentor(slug: $slug, userId: $request->user_id );
    
            return ResponseHelper::success(
                status: true,
                message: 'Mentor berhasil ditambahkan',
                result: null,
                statusCode: 201
            );

        } catch (\Exception $th) {
            return ResponseHelper::error(
                message: $th->getMessage(),
                statusCode: 500
            );
        }
    }
 
    /**
     * Toggle status mentor
     * 
     * Toggle status mentor berdasarkan slug course
     * 
     * @body User
     * @authenticated
     * @role:admin-pusat
     */
    public function toggleMentorActivation(string $slug, string $userId): JsonResponse
    {
        try {
            $this->courseMentorService->toggleMentor(slug: $slug, userId: $userId);

            return ResponseHelper::success(
                status: true,
                message: 'Status mentor berhasil diubah',
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

    /**
     * Hapus mentor
     * 
     * Hapus mentor berdasarkan slug course
     * 
     * @body User
     * @authenticated
     * @role:admin-pusat
     */
    public function destroy(string $slug, string $userId): JsonResponse
    {
        try {
            $this->courseMentorService->removeMentor(slug: $slug, userId: $userId);

            return ResponseHelper::success(
                status: true,
                message: 'Mentor berhasil dihapus dari course',
                result: null,
                statusCode: 200
            );
        } catch (\Exception $th) {
            return ResponseHelper::error(
                message: $th->getMessage(),
                statusCode: 500
            );
        }
    }
}
