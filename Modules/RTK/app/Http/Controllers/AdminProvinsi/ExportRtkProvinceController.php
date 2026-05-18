<?php

namespace Modules\RTK\Http\Controllers\AdminProvinsi;

use App\Exports\RtkProvinceExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\MasterData\Models\Province;
use Modules\RTK\Services\RTKDService;

class ExportRtkProvinceController extends Controller
{
    public function __construct(
        private RTKDService $rtkdService
    ) {}

    public function ExportRtkProvince(Request $request)
    {
        $user = Auth::user();
        $provinceName = Province::find($user->scopeArea->province_code)->name;
        $filename = 'Rencana Tenaga Kerja Daerah' . '-' . $provinceName . '-' . now()->format('Y-m-d') . '.xlsx';
        $isActive = $request->input('acuan');
        $isActive = ($isActive !== null && $isActive !== '') ? $isActive : null;

        return Excel::download(
            new RtkProvinceExport(
                provinceName: $provinceName,
                rtkdService: $this->rtkdService,
                provinceCode: $user->scopeArea->province_code,
                statusVerification: $request->string('status_verification')->toString() ?: null,
                statusDocument: $request->string('status_document')->toString() ?: null,
                isActive: $isActive,
                search: $request->string('search')->toString() ?: null,
            ),
            $filename
        );
    }
}
