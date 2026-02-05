<?php

namespace Modules\RTK\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RTK\Services\RTKDService;

class RencanaTenagaKerjaDaerahController extends Controller
{

    public function __construct(
        private RTKDService $rtkdService
    ) {}

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

        $rtkds = $this->rtkdService->paginateFilteredRTKDProvince($search, $orderBy, $limit, $status);
        return view('rtk::adminPusat.rtkd.index', compact('rtkds'));
    }
}
