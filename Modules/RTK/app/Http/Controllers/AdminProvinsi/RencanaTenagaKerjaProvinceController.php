<?php

namespace Modules\RTK\Http\Controllers\AdminProvinsi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Log;
use Modules\RTK\Http\Requests\RTKPStoreRequest;
use Modules\RTK\Http\Requests\RTKPUpdateRequest;
use Modules\RTK\Models\RencanaTenagaKerja;
use Modules\RTK\Services\RTKDService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\PermissionMiddleware;


class RencanaTenagaKerjaProvinceController extends Controller implements HasMiddleware
{

    public function __construct(
        private RTKDService $rtkdService
    ) {}


    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('rtkd-create|rtkd-edit|rtkd-delete|rtkd-view'), only: ['index']),
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
        $limit = $request->per_page ?? 10;
        $search = $request->string('search')->toString();
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';
        $year = $request->year;

        $statusVerification = $request->string('status_verifikasi')->toString();
        $statusDocument = $request->string('status_document')->toString();
        $isActive = $request->input('acuan');

        $user = Auth::user();
        if (!$user->hasCompleteScope()) {
            abort(403, 'Admin provinsi belum memiliki wilayah.');
        }
        $rtkdps = $this->rtkdService->paginateFilteredRTKDByProvinceCode(
            provinceCode: $user->scopeArea->province_code,
            search: $search,
            sortBy: $orderBy,
            limit: $limit,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive
        );
        return view('rtk::adminProvince.rtkp.index', compact('rtkdps'));
    }

    public function create()
    {
        return view('rtk::adminProvince.rtkp.create');
    }

    public function store(RTKPStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $rtkdp = $this->rtkdService->createRTKProvince(data: $validated);
            return redirect()->route('admin-province.rtkdp.index')->with('success', 'RTK Provinsi berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            ToastMagic::error('RTK Provinsi gagal ditambahkan!');
            return redirect()->route('admin-province.rtkdp.index')->with('error', 'RTK Provinsi gagal ditambahkan!');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(RencanaTenagaKerja $rtkdp)
    {
        return view('rtk::adminProvince.rtkp.show', [
            'rtkdp' => $rtkdp
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, RencanaTenagaKerja $rtkdp)
    {
        return view('rtk::adminProvince.rtkp.edit', [
            'rtkdp' => $rtkdp
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param RTKPUpdateRequest $request
     * @param RencanaTenagaKerja $rtkdp
     * @return RedirectResponse
     */
    public function update(RTKPUpdateRequest $request, RencanaTenagaKerja $rtkdp)
    {
        try {
            $validated = $request->validated();
            $rtkdp = $this->rtkdService->updateRTKProvince(rtk: $rtkdp, data: $validated);
            return redirect()->route('admin-province.rtkdp.index')->with('success', 'RTK Provinsi berhasil diupdate');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            ToastMagic::error('RTKDP gagal diupdate!', $e->getMessage());
            return redirect()->route('admin-province.rtkdp.index')->with('error', 'RTK Provinsi gagal diupdate!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RencanaTenagaKerja $rtkdp)
    {
        try {
            $this->rtkdService->deleteRTKD($rtkdp);
            return redirect()->route('admin-province.rtkdp.index')->with('success', 'RTK Provinsi berhasil dihapus!');
        } catch (\Throwable $th) {
            return redirect()->route('admin-province.rtkdp.index')->with('error', 'RTK Provinsi gagal dihapus!');
        }
    }
}
