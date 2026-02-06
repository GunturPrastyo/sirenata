<?php

namespace Modules\Dashboard\Http\Controllers\AdminProvinsi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return view('dashboard::admin-provinsi.index', [
            'user' => $user
        ]);
    }
}
