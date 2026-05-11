<?php

namespace Modules\RTK\Http\Controllers\AdminProvinsi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Modules\RTK\Services\RTKDService;
use Illuminate\Routing\Controllers\Middleware;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RencanaTenagaKerjaKabKotaController extends Controller implements HasMiddleware
{
    public function __construct(
        private RTKDService $rtkdService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('rtkd-view'), only: ['index']),
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

        $user = Auth::user();
        if (!$user->hasCompleteScope()) {
            abort(403, 'Admin provinsi belum memiliki wilayah.');
        }

        $rtkds = $this->rtkdService->paginateFilteredRTKKabKotaByProvince(
            provinceCode: $user->scopeArea->province_code,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            status: $status
        );

        return view('rtk::adminProvince.rtkd.index', compact('rtkds'));
    }

    public function showRegency(Request $request, string $regencyCode)
    {
        $limit = $request->per_page ?? 10;
        $search = $request->search;
        $status = $request->status;
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';
        $year = $request->year;
        $user = Auth::user();
        $regency = Regency::find($regencyCode);
        $province = Province::find($regency->province_code);


        $rtks = $this->rtkdService->paginateFilteredRTKDByKabKotaCode(
            provinceCode: $user->scopeArea->province_code,
            regencyCode: $regencyCode,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            statusVerification: $status
        );
        return view('rtk::adminProvince.rtkd.show-kab-kota', [
            'rtks' => $rtks,
            'regency' => $regency,
            'province' => $province,
            'regencyCode' => $regencyCode,
        ]);
    }
}
