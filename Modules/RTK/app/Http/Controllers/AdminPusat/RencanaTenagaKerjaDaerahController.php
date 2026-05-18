<?php

namespace Modules\RTK\Http\Controllers\AdminPusat;

use App\Exports\RtkAllProvinceExport;
use App\Exports\RtkProvinceExport;
use App\Exports\RtkRegencyByProvinceExport;
use App\Exports\RtkRegencyExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\RTK\Services\RTKDService;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;
use Modules\RTK\Models\RencanaTenagaKerja;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Log;

class RencanaTenagaKerjaDaerahController extends Controller implements HasMiddleware
{
    public function __construct(
        private RTKDService $rtkdService
    ) {}

    public static function middleware(): array
    {
        return [

            new Middleware(PermissionMiddleware::using('rtkd-view'), only: ['kabKota']),
        ];
    }

    /**
     * List Active RTK Province (Admin Pusat)
     */
    public function index(Request $request)
    {
        $limit              = $request->integer('per_page', 10);
        $search             = $request->string('search')->toString() ?: null;
        $statusVerification = $request->string('status_verification')->toString() ?: null;
        $statusDocument     = $request->string('status_document')->toString() ?: null;
        $isActive           = $request->input('acuan');
        $orderBy            = in_array($request->orderBy, ['asc', 'desc']) ? $request->orderBy : 'desc';

        $rtkds = $this->rtkdService->paginateFilteredRTKDProvince(
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive,
        );
        return view('rtk::adminPusat.rtkd.index', compact('rtkds'));
    }

    public function exportAllProvince(Request $request)
    {
        $filename = 'RTK-Provinsi-' . now()->format('Y-m-d') . '.xlsx';

        $isActive = $request->input('acuan');
        $isActive = ($isActive !== null && $isActive !== '') ? $isActive : null;

        return Excel::download(
            new RtkAllProvinceExport(
                search: $request->string('search')->toString() ?: null,
                statusVerification: $request->string('status_verification')->toString() ?: null,
                statusDocument: $request->string('status_document')->toString() ?: null,
                isActive: $isActive,
            ),
            $filename
        );
    }


    /**
     * List Active RTK Kab/Kota by Province (Admin Pusat)
     */
    public function kabKota(Request $request, string $provinceCode)
    {
        $limit              = $request->integer('per_page', 10);
        $search             = $request->string('search')->toString() ?: null;
        $statusVerification = $request->string('status_verification')->toString() ?: null;
        $statusDocument     = $request->string('status_document')->toString() ?: null;
        $isActive           = $request->input('acuan');
        $orderBy            = in_array($request->orderBy, ['asc', 'desc']) ? $request->orderBy : 'desc';

        $rtkds = $this->rtkdService
            ->paginateFilteredRTKKabKotaByProvince(
                provinceCode: $provinceCode,
                search: $search,
                sortBy: $orderBy,
                limit: $limit,
                statusVerification: $statusVerification,
                statusDocument: $statusDocument,
                isActive: $isActive,
            );
        $province = Province::find($provinceCode);
        return view('rtk::adminPusat.rtkd.kab-kota', compact('rtkds', 'provinceCode', 'province'));
    }

    public function exportRegencyByProvince(Request $request, string $provinceCode)
    {
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

    /**
     * Show RTK province Document (Admin Pusat)
     */
    public function showProvince(Request $request, string $provinceCode)
    {
        $limit              = $request->integer('per_page', 10);
        $search             = $request->string('search')->toString() ?: null;
        $statusVerification = $request->string('status_verification')->toString() ?: null;
        $statusDocument     = $request->string('status_document')->toString() ?: null;
        $isActive           = $request->input('acuan');
        $orderBy            = in_array($request->orderBy, ['asc', 'desc']) ? $request->orderBy : 'desc';
        $province = Province::find($provinceCode);

        $rtks = $this->rtkdService->paginateFilteredRTKDByProvinceCode(
            provinceCode: $provinceCode,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive,
        );
        return view('rtk::adminPusat.rtkd.show-province', [
            'rtks' => $rtks,
            'province' => $province,
            'provinceCode' => $provinceCode,
        ]);
    }

    public function editProvince(string $provinceCode, RencanaTenagaKerja $rtkdp)
    {
        $province = Province::find($provinceCode);
        return view('rtk::adminPusat.rtkd.edit-province', [
            'rtkdp'       => $rtkdp,
            'province'    => $province,
            'provinceCode' => $provinceCode,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param RencanaTenagaKerja $rtkdp
     * @return RedirectResponse
     */
    public function updateProvince(Request $request, string $provinceCode, RencanaTenagaKerja $rtkdp)
    {
        try {
            $validated = $request->validate([
                'is_active' => ['required', 'boolean']
            ]);

            $this->rtkdService->updateIsActiveProvince(rtk: $rtkdp, isActive: $validated['is_active']);
            return redirect()->route('admin-pusat.rtkd.show-province', $provinceCode);
        } catch (\Exception $e) {
            ToastMagic::error('RTK Provinsi gagal diupdate!', $e->getMessage());
            return redirect()->route('admin-pusat.rtkd.show-province', $provinceCode);
        }
    }

    public function ExportRtkProvince(Request $request, string $provinceCode)
    {
        $provinceName = Province::find($provinceCode)->name;
        $filename = 'Rencana Tenaga Kerja Daerah' . '-' . $provinceName . '-' . now()->format('Y-m-d') . '.xlsx';
        $isActive = $request->input('acuan');
        $isActive = ($isActive !== null && $isActive !== '') ? $isActive : null;
        return Excel::download(
            new RtkProvinceExport(
                provinceName: $provinceName,
                rtkdService: $this->rtkdService,
                provinceCode: $provinceCode,
                statusVerification: $request->string('status_verification')->toString() ?: null,
                statusDocument: $request->string('status_document')->toString() ?: null,
                isActive: $isActive,
                search: $request->string('search')->toString() ?: null,
            ),
            $filename
        );
    }

    /**
     * Show RTK Document (Admin Pusat)
     */
    public function showRegency(Request $request, string $regencyCode)
    {
        $limit              = $request->integer('per_page', 10);
        $search             = $request->string('search')->toString() ?: null;
        $statusVerification = $request->string('status_verification')->toString() ?: null;
        $statusDocument     = $request->string('status_document')->toString() ?: null;
        $isActive           = $request->input('acuan');
        $orderBy            = in_array($request->orderBy, ['asc', 'desc']) ? $request->orderBy : 'desc';

        $regency = Regency::find($regencyCode);
        $province = Province::find($regency->province_code);


        $rtks = $this->rtkdService->paginateFilteredRTKDByKabKotaCode(
            provinceCode: $regency->province_code,
            regencyCode: $regencyCode,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive,
        );
        return view('rtk::adminPusat.rtkd.show-kab-kota', [
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
        return view('rtk::adminPusat.rtkd.edit-kab-kota', [
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
            return redirect()->route('admin-pusat.rtkd.show-regency', $regencyCode);
        } catch (\Exception $e) {
            ToastMagic::error('RTK Kabupaten/Kota gagal diupdate!', $e->getMessage());
            return redirect()->route('admin-pusat.rtkd.show-regency', $regencyCode);
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
