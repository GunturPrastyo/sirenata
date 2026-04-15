<?php

namespace Modules\LMS\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Http\Request;
use Modules\LMS\Http\Requests\Api\StoreCourseBenefitRequest;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseBenefits;
use Modules\LMS\Transformers\Api\CourseBenefitResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseBenefitController extends Controller
{
    /**
     * List semua benefit
     * 
     * Menampilkan benefit berdasarkan slug course
     * 
     * @unauthenticated
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
     * Buat benefit baru
     * 
     * Buat benefit baru berdasarkan slug course. Hanya bisa diakses oleh admin-pusat.
     * 
     * @authenticated
     * @role:admin-pusat
     */
    #[BodyParameter('name', description: 'Name Benefit dari Course.', type: 'string', required: true, example: 'test-1')]
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
     * Update benefit
     * 
     * Update benefit berdasarkan slug course. Hanya bisa diakses oleh admin-pusat.
     * 
     * @body CourseBenefits
     * @authenticated
     * @role:admin-pusat
     */
    #[BodyParameter('name', description: 'Name Benefit dari Course.', type: 'string', required: true, example: 'test-1')]
    public function update(StoreCourseBenefitRequest $request, CourseBenefits $benefitId): JsonResponse
    {
        $data   = $request->validated();
        $benefitId->update($data);

        return response()->json([
            'message' => 'Benefit berhasil diupdate',
            'data'    => new CourseBenefitResource($benefitId),
        ], 201);
    }

    /**
     * Hapus benefit
     * 
     * Hapus benefit berdasarkan slug course. Hanya bisa diakses oleh admin-pusat.
     * 
     * @body CourseBenefits
     * @authenticated
     * @role:admin-pusat
     */
    public function destroy(CourseBenefits $benefitId): JsonResponse
    {
        // Pastikan benefit milik course yang benar
        $course = $benefitId->course->id;
        abort_if($benefitId->course_id !== $course, 404, 'Benefit tidak ditemukan');
        $benefitId->delete();

        return response()->json([
            'message' => 'Benefit berhasil dihapus',
        ]);
    }
}
