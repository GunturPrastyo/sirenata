<?php

namespace Modules\RTK\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Http\Requests\RTKNStoreRequest;
use Modules\RTK\Http\Requests\RTKNUpdateRequest;
use Modules\RTK\Models\RencanaTenagaKerja;
use Modules\RTK\Services\RTKNService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RencanaTenagaKerjaNasionalController extends Controller implements HasMiddleware
{

    public function __construct(
        private RTKNService $rtknService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('rtkn-list|rtkn-create|rtkn-edit|rtkn-delete'), only: ['index']),
            new Middleware(PermissionMiddleware::using('rtkn-view'), only: ['show']),
            new Middleware(PermissionMiddleware::using('rtkn-create'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('rtkn-edit'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('rtkn-delete'), only: ['destroy']),
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

        $rtkns = $this->rtknService->paginateFilteredRTKN($search, $orderBy, $limit, $status);
        return view('rtk::adminPusat.rtkn.index', [
            'rtkns' => $rtkns
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rtk::adminPusat.rtkn.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RTKNStoreRequest $request)
    {
        $validated = $request->validated();
        $this->rtknService->createRTKN($validated);
        return redirect()->route('admin-pusat.rtkn.index')->with('success', 'RTKN berhasil ditambahkan');
    }

    /**
     * Show the specified resource.
     */
    public function show(RencanaTenagaKerja $rencanaTenagaKerjaNasional)
    {
        return view('rtk::adminPusat.rtkn.show', [
            'rtkn' => $rencanaTenagaKerjaNasional
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, RencanaTenagaKerja $rencanaTenagaKerjaNasional) 
    {
        return view('rtk::adminPusat.rtkn.edit', [
            'rtkn' => $rencanaTenagaKerjaNasional
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(RTKNUpdateRequest $request, RencanaTenagaKerja $rencanaTenagaKerjaNasional)
    {
        $validated = $request->validated();
        $this->rtknService->updateRTKN($validated, $rencanaTenagaKerjaNasional);
        return redirect()->route('admin-pusat.rtkn.index')->with('success', 'RTKN berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RencanaTenagaKerja $rencanaTenagaKerjaNasional)
    {
        $this->rtknService->deleteRTKN($rencanaTenagaKerjaNasional);
        return redirect()->route('admin-pusat.rtkn.index')->with('success', 'RTKN berhasil dihapus');
    }
}
