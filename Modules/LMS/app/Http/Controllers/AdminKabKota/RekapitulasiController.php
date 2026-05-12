<?php

namespace Modules\LMS\Http\Controllers\AdminKabKota;

use App\Exports\RekapUserCourseRegencyExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\LMS\Services\CourseService;
use Modules\LMS\Services\RekapitulasiService;
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
        $regency = Regency::find($user->scopeArea?->regency_code);
        $limit   = $request->integer('per_page', 10);
        $search  = $request->string('search')->toString();
        $courseId = $request->string('course_id')->toString();

        $courses = $this->courseService->getCoursesForFilter();
        $data = $this->courseService->paginateCourseEnrollmentsByRegency(
            regencyCode: $user->scopeArea->regency_code,
            search: $search,
            limit: $limit,
            courseId: $courseId,
        );
        return view('lms::admin-kab-kota.sdm.rekapitulasi-index', [
            'data' => $data,
            'regencyCode' => $user->scopeArea?->regency_code,
            'regency' => $regency,
            'courses' => $courses,
        ]);
    }

    /**
     * Export rekap user course per provinsi
     */
    public function exportRekapUserRegency(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasCompleteScope()) {
            abort(403, 'Admin provinsi belum memiliki wilayah.');
        }
        $regencyCode = $user->scopeArea?->regency_code;
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
}
