<?php

namespace Modules\RTK\Http\Controllers\AdminProvinsi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\RTK\Services\RTKDService;

class RencanaTenagaKerjaKabKotaController extends Controller
{
    public function __construct(
        private RTKDService $rtkdService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->per_page ?? 10;
        $search = $request->search;
        $status = $request->status;
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';
        $year = $request->year;
        $provinceCode = Auth::user()->scopeArea?->province_code;

        $rtkds = $this->rtkdService->paginateFilteredRTKKabKotaByProvince(
            provinceCode: $provinceCode,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            status: $status
        );

        return view('rtk::adminProvince.rtkd.index', compact('rtkds'));
    }
}
