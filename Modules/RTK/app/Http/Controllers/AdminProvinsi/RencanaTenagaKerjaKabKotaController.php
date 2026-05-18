<?php

namespace Modules\RTK\Http\Controllers\AdminProvinsi;

use App\Exports\RtkRegencyByProvinceExport;
use App\Exports\RtkRegencyExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Modules\RTK\Services\RTKDService;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;
use Modules\RTK\Models\RencanaTenagaKerja;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
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
        $limit              = $request->integer('per_page', 10);
        $search             = $request->string('search')->toString() ?: null;
        $statusVerification = $request->string('status_verification')->toString() ?: null;
        $statusDocument     = $request->string('status_document')->toString() ?: null;
        $isActive           = $request->input('acuan');
        $orderBy            = in_array($request->orderBy, ['asc', 'desc']) ? $request->orderBy : 'desc';

        $user = Auth::user();
        if (!$user->hasCompleteScope()) {
            abort(403, 'Admin provinsi belum memiliki wilayah.');
        }

        $rtkds = $this->rtkdService->paginateFilteredRTKKabKotaByProvince(
            provinceCode: $user->scopeArea->province_code,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive,
        );

        return view('rtk::adminProvince.rtkd.index', compact('rtkds'));
    }

    public function exportRegencyByProvince(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasCompleteScope()) {
            abort(403, 'Admin provinsi belum memiliki wilayah.');
        }
        $provinceCode = $user->scopeArea->province_code;
        $province = Province::find($provinceCode);
        $filename = 'RTK-Kab-Kota-' . $province->name . ' - ' . now()->format('Y-m-d') . '.xlsx';

        $isActive = $request->input('acuan');
        $isActive = ($isActive !== null && $isActive !== '') ? $isActive : null;

        return Excel::download(
            new RtkRegencyByProvinceExport(
                provinceCode: $provinceCode,
                search: $request->string('search')->toString() ?: null,
                statusVerification: $request->string('status_verification')->toString() ?: null,
                statusDocument: $request->string('status_document')->toString() ?: null,
                isActive: $isActive,
            ),
            $filename
        );
    }

    public function showRegency(Request $request, string $regencyCode)
    {
        $limit              = $request->integer('per_page', 10);
        $search             = $request->string('search')->toString() ?: null;
        $statusVerification = $request->string('status_verification')->toString() ?: null;
        $statusDocument     = $request->string('status_document')->toString() ?: null;
        $isActive           = $request->input('acuan');
        $orderBy            = in_array($request->orderBy, ['asc', 'desc']) ? $request->orderBy : 'desc';
        $user = Auth::user();
        $regency = Regency::find($regencyCode);
        $province = Province::find($regency->province_code);


        $rtks = $this->rtkdService->paginateFilteredRTKDByKabKotaCode(
            provinceCode: $user->scopeArea->province_code,
            regencyCode: $regencyCode,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive,
        );
        return view('rtk::adminProvince.rtkd.show-kab-kota', [
            'rtks' => $rtks,
            'regency' => $regency,
            'province' => $province,
            'regencyCode' => $regencyCode,
        ]);
    }

    public function editRegency(string $regencyCode, RencanaTenagaKerja $rtkdp)
    {
        $regency = Regency::find($regencyCode);
        $province = Province::find($regency->province_code);
        return view('rtk::adminProvince.rtkd.edit-kab-kota', [
            'rtkdp'       => $rtkdp,
            'regency'    => $regency,
            'province' => $province,
            'provinceCode' => $regency->province_code,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param RencanaTenagaKerja $rtkdp
     * @return RedirectResponse
     */
    public function updateRegency(Request $request, string $regencyCode, RencanaTenagaKerja $rtkdp)
    {
        try {
            $validated = $request->validate([
                'is_active' => ['required', 'boolean']
            ]);

            $this->rtkdService->updateIsActiveKabKota(rtk: $rtkdp, isActive: $validated['is_active']);
            return redirect()->route('admin-province.laporan.show-regency', $regencyCode);
        } catch (\Exception $e) {
            ToastMagic::error('RTK Kabupaten/Kota gagal diupdate!', $e->getMessage());
            return redirect()->route('admin-province.laporan.show-regency', $regencyCode);
        }
    }

    public function ExportRtkRegency(Request $request, string $regencyCode)
    {
        $regencyName = Regency::find($regencyCode)->name;
        $filename = 'Rencana Tenaga Kerja Daerah' . '-' . $regencyName . '-' . now()->format('Y-m-d') . '.xlsx';
        $isActive = $request->input('acuan');
        $isActive = ($isActive !== null && $isActive !== '') ? $isActive : null;
        $regency = Regency::find($regencyCode);

        $provinceCode = $regency->province_code;
        return Excel::download(
            new RtkRegencyExport(
                regencyName: $regencyName,
                rtkdService: $this->rtkdService,
                provinceCode: $provinceCode,
                regencyCode: $regencyCode,
                statusVerification: $request->string('status_verification')->toString() ?: null,
                statusDocument: $request->string('status_document')->toString() ?: null,
                isActive: $isActive,
                search: $request->string('search')->toString() ?: null,
            ),
            $filename
        );
    }
}
