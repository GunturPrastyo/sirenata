<?php

namespace Modules\RTK\Http\Controllers\AdminKabKota;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Modules\RTK\Services\RTKDService;
use Modules\RTK\Models\RencanaTenagaKerja;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Modules\RTK\Http\Requests\AdminPusat\RTKPStoreRequest;
use Modules\RTK\Http\Requests\AdminPusat\RTKPUpdateRequest;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RencanaTenagaKerjaKabKotaController extends Controller implements HasMiddleware
{

    public function __construct(
        private RTKDService $rtkdService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('rtkd-list|rtkd-create|rtkd-edit|rtkd-delete'), only: ['index']),
            new Middleware(PermissionMiddleware::using('rtkd-view'), only: ['show']),
            new Middleware(PermissionMiddleware::using('rtkd-create'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('rtkd-edit'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('rtkd-delete'), only: ['destroy']),
        ];
    } 

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit   = $request->integer('per_page', 10);
        $search  = $request->string('search')->toString();
        $status  = $request->string('status')->toString();
        $orderBy = in_array($request->orderBy, ['asc', 'desc']) ? $request->orderBy : 'desc';

        $user = Auth::user();
        
        if (!$user->hasCompleteScope()) {
            abort(403, 'Admin kab/kota belum memiliki wilayah.');
        }

        $rtkds = $this->rtkdService->paginateFilteredRTKDByKabKotaCode(
            provinceCode: $user->scopeArea->province_code,
            regencyCode: $user->scopeArea->regency_code,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            status: $status
        );
        return view('rtk::adminKabKota.rtk.index', compact('rtkds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rtk::adminKabKota.rtk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RTKPStoreRequest $request) {
        try {
            $validated = $request->validated();
            $this->rtkdService->createRTKKabKota($validated);
            ToastMagic::success("RTK Kab/Kota berhasil ditambahkan!");
            return redirect()->route('admin-kab-kota.rtkd.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            ToastMagic::error('RTK gagal ditambahkan!');
            return redirect()->route('admin-kab-kota.rtkd.index')->with('error', 'RTK gagal ditambahkan!');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('rtk::adminKabKota.rtk.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, RencanaTenagaKerja $rtkd)
    {
        return view('rtk::adminKabKota.rtk.edit', [
            'rtkd' => $rtkd
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RTKPUpdateRequest $request, RencanaTenagaKerja $rtkd)
    {
        try {
            $validated = $request->validated();
            $this->rtkdService->updateRTKKabKota($rtkd, $validated);
            ToastMagic::success("RTK Kab/Kota berhasil diupdate!");
            return redirect()->route('admin-kab-kota.rtkd.index')->with('success', 'Data berhasil diupdate');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            ToastMagic::error('RTK gagal diupdate!');
            return redirect()->route('admin-kab-kota.rtkd.index')->with('error', 'RTK gagal diupdate!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RencanaTenagaKerja $rtkd) {
        try {
            $this->rtkdService->deleteRTKD($rtkd);
            return redirect()->route('admin-kab-kota.rtkd.index')->with('success', 'RTKDP berhasil dihapus!');
        } catch (\Throwable $th) {
            return redirect()->route('admin-kab-kota.rtkd.index')->with('error', 'RTKDP gagal dihapus!');
        }
    }
}
