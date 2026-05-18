<?php

namespace Modules\RTK\Http\Controllers\AdminKabKota;

use App\Exports\RtkRegencyExport;
use App\Http\Controllers\Controller;
use Creasi\Nusa\Models\Regency as ModelsRegency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\MasterData\Models\Regency;
use Modules\RTK\Services\RTKDService;

class ExportRtkRegencyController extends Controller
{
    public function __construct(
        private RTKDService $rtkdService
    ) {}

    public function ExportRtkRegency(Request $request)
    {
        $user = Auth::user();
        $regencyName = Regency::find($user->scopeArea->regency_code)->name;
        $filename = 'Rencana Tenaga Kerja Daerah' . '-' . $regencyName . '-' . now()->format('Y-m-d') . '.xlsx';
        $isActive = $request->input('acuan');
        $isActive = ($isActive !== null && $isActive !== '') ? $isActive : null;
        $regency = ModelsRegency::find($user->scopeArea->regency_code);
        $provinceCode = $regency->province_code;

        return Excel::download(
            new RtkRegencyExport(
                regencyName: $regencyName,
                rtkdService: $this->rtkdService,
                provinceCode: $provinceCode,
                regencyCode: $user->scopeArea->regency_code,
                statusVerification: $request->string('status_verification')->toString() ?: null,
                statusDocument: $request->string('status_document')->toString() ?: null,
                isActive: $isActive,
                search: $request->string('search')->toString() ?: null,
            ),
            $filename
        );
    }
}
