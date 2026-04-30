<?php

namespace Modules\LMS\Http\Controllers\AdminProvince;

use App\Exports\RekapUserCourseProvinceExport;
use App\Exports\RekapUserCourseRegencyExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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
        $user = Auth::user();
        if (!$user->hasCompleteScope()) {
            abort(403, 'Admin provinsi belum memiliki wilayah.');
        }
        $limit   = $request->per_page ?? 10;
        $search  = $request->search;
        $data = $this->rekapitulasiService
            ->paginateFilteredRekapitulasiRegency(
                provinceCode: $user->scopeArea->province_code,
                search: $search,
                limit: $limit,
            );

        return view('lms::admin-province.sdm.rekapitulasi-kab-kota', compact('data', 'user'));
    }

    public function rekapUserKabKota(Request $request, string $regencyCode)
    {
        $regency = Regency::find($regencyCode);
        $limit   = $request->integer('per_page', 10);
        $search  = $request->string('search')->toString();
        $courseId = $request->string('course_id')->toString();

        $courses = $this->courseService->getCoursesForFilter();
        $data = $this->courseService->paginateCourseEnrollmentsByRegency(
            regencyCode: $regencyCode,
            search: $search,
            limit: $limit,
            courseId: $courseId,
        );

        return view('lms::admin-province.sdm.rekapitulasi-user-kab-kota', [
            'data' => $data,
            'regencyCode' => $regencyCode,
            'regency' => $regency,
            'courses' => $courses,
        ]);
    }

    /**
     * Export rekap user course per provinsi
     */
    public function exportRekapUserRegency(Request $request, string $regencyCode)
    {
        $regencyName = Regency::find($regencyCode)->name;
        $filename     = 'rekapitulasi-' . str($regencyName)->slug() . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new RekapUserCourseRegencyExport(
                regencyName: $regencyName,
                courseService: $this->courseService,
                regencyCode: $regencyCode,
                courseId: $request->string('course_id')->toString() ?: null,
                search: $request->string('search')->toString() ?: null,
            ),
            $filename
        );
    }

    public function rekapUserProvince(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasCompleteScope()) {
            abort(403, 'Admin provinsi belum memiliki wilayah.');
        }
        $provinceCode = $user->scopeArea?->province_code;
        $provinceName = Province::find($provinceCode)->name;
        $limit   = $request->integer('per_page', 10);
        $search  = $request->string('search')->toString();
        $courseId = $request->string('course_id')->toString();

        $courses = $this->courseService->getCoursesForFilter();
        $data = $this->courseService->paginateCourseEnrollmentsByProvince(
            provinceCode: $user->scopeArea->province_code,
            search: $search,
            limit: $limit,
            courseId: $courseId,
        );

        return view('lms::admin-province.sdm.rekapitulasi-user-province', [
            'data' => $data,
            'provinceCode' => $provinceCode,
            'provinceName' => $provinceName,
            'courses' => $courses,
        ]);
    }


    /**
     * Export rekap user course per provinsi
     */
    public function exportRekapUserProvince(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasCompleteScope()) {
            abort(403, 'Admin provinsi belum memiliki wilayah.');
        }
        $provinceCode = $user->scopeArea?->province_code;
        $provinceName = Province::find($provinceCode)->name;
        $filename     = 'rekapitulasi-provinsi-' . str($provinceName)->slug() . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new RekapUserCourseProvinceExport(
                provinceName: $provinceName,
                courseService: $this->courseService,
                provinceCode: $provinceCode,
                search: $request->string('search')->toString() ?: null,
                courseId: $request->string('course_id')->toString() ?: null,
            ),
            $filename
        );
    }
}
