<?php

namespace Modules\RTK\Http\Controllers\AdminKabKota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Services\RTKDService;

class RtkKabKotaDashboardController extends Controller
{

    public function __construct(
        private RTKDService $rtkdService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rtkKabKotaActive = $this->rtkdService->rtkKabKotaActive();
        // dd($rtkKabKotaActive->isExpired());
        return view('rtk::adminKabKota.rtk.laporan', compact('rtkKabKotaActive'));
    }
}
