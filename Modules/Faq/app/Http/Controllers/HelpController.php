<?php

namespace Modules\Faq\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Faq\Models\Faq;

class HelpController extends Controller
{
    /**
     * Display the Help / FAQ page dynamically based on user role.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Faq::with('creator')->latest();

        if ($user->hasRole('user')) {
            $userScope = $user->scopeArea;
            $dashboardRoute = 'user.dashboard';

            if ($userScope && $userScope->province_code && !$userScope->regency_code) {
                $query->where('level', 'provinsi');
            } elseif ($userScope && $userScope->regency_code) {
                $query->where('level', 'kab_kota');
            } else {
                $query->where('level', 'pusat');
            }
        } elseif (!$user->hasRole('admin-pusat')) {
            $dashboardRoute = 'dashboard'; // Set a default dashboard route for admin-province/kab-kota
            if ($user->hasRole('admin-province')) {
                $dashboardRoute = 'admin-province.dashboard';
                $query->where('level', 'provinsi');
            } elseif ($user->hasRole('admin-kab-kota')) {
                $dashboardRoute = 'admin-kab-kota.dashboard';
                $query->where('level', 'kab_kota');
            } else {
                $query->where('id', -1);
            }
        } else { // It's admin-pusat
           $dashboardRoute = 'dashboard'; // Or appropriate route for admin-pusat
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', '%' . $search . '%')
                    ->orWhere('answer', 'like', '%' . $search . '%');
            });
        }

        $faqs = $query->paginate(10);

        return view('faq::help.index', compact('faqs', 'dashboardRoute'));
    }
}
