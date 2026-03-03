<?php

namespace Modules\LMS\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Services\CourseService;
use Modules\LMS\Services\RekapitulasiService;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;

class RekapitulasiController extends Controller
{

    public function __construct(
        private RekapitulasiService $rekapitulasiService,
        private CourseService $courseService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit   = $request->integer('per_page', 10);
        $search  = $request->string('search')->toString();

        $data = $this->rekapitulasiService->paginateFilteredRekapitulasiProvince(
            search: $search,
            limit: $limit
        );
        return view('lms::admin-pusat.sdm.rekapitulasi-index', compact('data'));
    }

    public function kabKota(Request $request, string $provinceCode) {
        $limit   = $request->integer('per_page', 10);
        $search  = $request->string('search')->toString();

        $data = $this->rekapitulasiService
            ->paginateFilteredRekapitulasiRegency(
                provinceCode: $provinceCode,
                search: $search,
                limit: $limit,
            );
        return view('lms::admin-pusat.sdm.rekapitulasi-kab-kota', compact('data', 'provinceCode'));
    }
    

    public function rekapUserProvince(Request $request, string $provinceCode) {
        $provinceName = Province::find($provinceCode)->name;
        $limit   = $request->integer('per_page', 10);
        $search  = $request->string('search')->toString();

        $data = $this->courseService->paginatedCourseByProvince(
            provinceCode: $provinceCode,
            search: $search,
            limit: $limit,
        );
        return view('lms::admin-pusat.sdm.rekapitulasi-user-province', compact('data', 'provinceCode', 'provinceName'));
    }

    public function rekapUserKabKota(Request $request, string $regencyCode) {
        $regency = Regency::find($regencyCode);
        $limit   = $request->integer('per_page', 10);
        $search  = $request->string('search')->toString();

        $data = $this->courseService->paginatedCourseByRegency(
            regencyCode: $regencyCode,
            search: $search,
            limit: $limit,
        );
        return view('lms::admin-pusat.sdm.rekapitulasi-user-kab-kota', compact('data', 'regencyCode', 'regency'));
    }
}
