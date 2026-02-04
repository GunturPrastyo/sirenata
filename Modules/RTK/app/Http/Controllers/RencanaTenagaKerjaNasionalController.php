<?php

namespace Modules\RTK\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Http\Requests\RTKNStoreRequest;
use Modules\RTK\Http\Requests\RTKNUpdateRequest;
use Modules\RTK\Http\Requests\RTKNUploadRequest;
use Modules\RTK\Services\RTKNService;
use Modules\User\Services\UserService;

class RencanaTenagaKerjaNasionalController extends Controller
{

    public function __construct(
        private RTKNService $rtknService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->per_page ?? 10;
        $search = $request->search;
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';

        $rtkns = $this->rtknService->paginateFilteredRTKN($search, $orderBy, $limit);
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
        return redirect()->route('admin-pusat.rencana-tenaga-kerja-nasional.index')->with('success', 'RTKN berhasil ditambahkan');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('rtk::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('rtk::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RTKNUpdateRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
