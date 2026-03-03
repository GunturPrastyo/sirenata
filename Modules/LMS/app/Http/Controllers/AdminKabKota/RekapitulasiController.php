<?php

namespace Modules\LMS\Http\Controllers\AdminKabKota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $regency = Regency::find($user->scopeArea->regency_code);
        $limit   = $request->integer('per_page', 10);
        $search  = $request->string('search')->toString();

        $data = $this->courseService->paginatedCourseByRegency(
            regencyCode: $user->scopeArea->regency_code,
            search: $search,
            limit: $limit,
        );
        return view('lms::admin-kab-kota.sdm.rekapitulasi-index', compact('data', 'regency'));
    }
}
