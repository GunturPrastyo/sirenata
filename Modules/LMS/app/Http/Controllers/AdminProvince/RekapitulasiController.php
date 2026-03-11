<?php

namespace Modules\LMS\Http\Controllers\AdminProvince;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('lms::admin-province.sdm.rekapitulasi-kab-kota',compact('data', 'user'));
    }

    public function rekapUserKabKota(Request $request, string $regencyCode) {
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

    public function rekapUserProvince(Request $request) {
        $user = Auth::user();
        if (!$user->hasCompleteScope()) {
            abort(403, 'Admin provinsi belum memiliki wilayah.');
        }        
        $provinceName = Province::find($user->scopeArea?->province_code)->name;
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
            'provinceCode' => $user->scopeArea->province_code, 
            'provinceName' => $provinceName,
            'courses' => $courses,
        ]);
    }
}
