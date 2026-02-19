<?php

namespace Modules\RTK\Http\Controllers\AdminProvinsi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Modules\RTK\Services\RTKDService;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RencanaTenagaKerjaKabKotaController extends Controller implements HasMiddleware
{
    public function __construct(
        private RTKDService $rtkdService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('rtkd-list|rtkd-view'), only: ['index']),
        ];
    } 

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
