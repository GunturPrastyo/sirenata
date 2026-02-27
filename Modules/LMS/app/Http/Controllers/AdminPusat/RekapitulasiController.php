<?php

namespace Modules\LMS\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Services\RekapitulasiService;

class RekapitulasiController extends Controller
{

    public function __construct(private RekapitulasiService $rekapitulasiService) {}

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
        return view('lms::admin-pusat.rekapitulasi-index', compact('data'));
    }

    public function kabKota(Request $request, string $provinceCode) {
        $limit   = $request->per_page ?? 10;
        $search  = $request->search;

        $data = $this->rekapitulasiService
            ->paginateFilteredRekapitulasiRegency(
                provinceCode: $provinceCode,
                search: $search,
                limit: $limit,
            );
        return view('lms::admin-pusat.rekapitulasi-kab-kota', compact('data', 'provinceCode'));
    }
    
}
