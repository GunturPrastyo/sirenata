<?php

namespace Modules\RTK\Http\Controllers\AdminPusat\RtkNasional;

use App\Exports\RtknExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\RTK\Services\RTKNService;

class ExportRtknController extends Controller
{
    public function __construct(
        private RTKNService $rtknService,
    ) {}

    public function ExportRtkn(Request $request)
    {
        $filename = 'Rencana Tenaga Kerja Nasional' . '-' . now()->format('Y-m-d') . '.xlsx';
        $isActive = $request->input('acuan');
        $isActive = ($isActive !== null && $isActive !== '') ? $isActive : null;
        return Excel::download(
            new RtknExport(
                rtknService: $this->rtknService,
                statusVerification: $request->string('status_verification')->toString() ?: null,
                statusDocument: $request->string('status_document')->toString() ?: null,
                isActive: $isActive,
                search: $request->string('search')->toString() ?: null,
            ),
            $filename
        );
    }
}
