<?php

namespace Modules\RTK\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Services\RTKDService;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;

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
     * List Active RTK Province (Admin Pusat)
     */
    public function index(Request $request)
    {
        $limit = $request->per_page ?? 10;
        $search = $request->search;
        $status = $request->status;
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';

        $rtkds = $this->rtkdService->paginateFilteredRTKDProvince(
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            status: $status
        );
        // dd($rtkds);
        return view('rtk::adminPusat.rtkd.index', compact('rtkds'));
    }

    /**
     * List Active RTK Kab/Kota by Province (Admin Pusat)
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

    /**
     * Show RTK province Document (Admin Pusat)
     */
    public function showProvince(Request $request, string $provinceCode) {
        $limit = $request->per_page ?? 10;
        $search = $request->search;
        $status = $request->status;
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';
        $year = $request->year;
        $province = Province::find($provinceCode);

        $rtks = $this->rtkdService->paginateFilteredRTKDByProvinceCode(
            provinceCode: $provinceCode,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            status: $status,
            year: $year
        );
        return view('rtk::adminPusat.rtkd.show-province', [
            'rtks' => $rtks,
            'province' => $province,
            'provinceCode' => $provinceCode,
        ]);
    }

    /**
     * Show RTK Document (Admin Pusat)
     */
    public function showRegency(Request $request, string $regencyCode) {
        $limit = $request->per_page ?? 10;
        $search = $request->search;
        $status = $request->status;
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';
        $year = $request->year;
        $regency = Regency::find($regencyCode);
        $province = Province::find($regency->province_code);
        

        $rtks = $this->rtkdService->paginateFilteredRTKDByKabKotaCode(
            provinceCode: $regency->province_code,
            regencyCode: $regencyCode,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            status: $status
        );
        return view('rtk::adminPusat.rtkd.show-kab-kota', [
            'rtks' => $rtks,
            'regency' => $regency,
            'province' => $province,
            'regencyCode' => $regencyCode,
        ]);
    }
}
