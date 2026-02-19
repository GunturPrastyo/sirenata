<?php

namespace Modules\RTK\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Services\RTKDService;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RencanaTenagaKerjaDaerahController extends Controller implements HasMiddleware
{
    public function __construct(
        private RTKDService $rtkdService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('rtkd-list'), only: ['index']),
            new Middleware(PermissionMiddleware::using('rtkd-view'), only: ['kabKota']),
        ];
    }   

    /**
     * List latest RTK Province (Admin Pusat)
     */
    public function index(Request $request)
    {
        $limit = $request->per_page ?? 10;
        $search = $request->search;
        $status = $request->status;
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';

        $rtkds = $this->rtkdService->paginateFilteredRTKDProvince($search, $orderBy, $limit, $status);
        return view('rtk::adminPusat.rtkd.index', compact('rtkds'));
    }

    /**
     * List latest RTK Kab/Kota by Province (Admin Pusat)
     */
    public function kabKota(Request $request, string $provinceCode) {
        $limit   = $request->per_page ?? 10;
        $search  = $request->search;
        $status  = $request->status;
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';

        $rtkds = $this->rtkdService
            ->paginateFilteredRTKKabKotaByProvince(
                provinceCode: $provinceCode,
                search: $search,
                sortBy: $orderBy,
                limit: $limit,
                status: $status
            );

        return view('rtk::adminPusat.rtkd.kab-kota', compact('rtkds', 'provinceCode'));
    }
}
