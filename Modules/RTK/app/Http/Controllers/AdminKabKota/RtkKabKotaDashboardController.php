<?php

namespace Modules\RTK\Http\Controllers\AdminKabKota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Services\RTKDService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RtkKabKotaDashboardController extends Controller implements HasMiddleware
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
    public function index()
    {
        $rtkKabKotaActive = $this->rtkdService->rtkKabKotaActive();
        return view('rtk::adminKabKota.rtk.laporan', compact('rtkKabKotaActive'));
    }
}
