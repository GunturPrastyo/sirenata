<?php

namespace Modules\LMS\Http\Controllers\AdminProvince;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Services\RekapitulasiService;

class RekapitulasiController extends Controller
{

    public function __construct(private RekapitulasiService $rekapitulasiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $provinceCode = $user->scopeArea->province_code;
        $limit   = $request->per_page ?? 10;
        $search  = $request->search;

        $data = $this->rekapitulasiService
            ->paginateFilteredRekapitulasiRegency(
                provinceCode: $provinceCode,
                search: $search,
                limit: $limit,
            );

        return view('lms::admin-province.sdm.rekapitulasi-kab-kota',compact('data'));
    }
}
