<?php

namespace Modules\Faq\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Faq\Models\Faq;
use Modules\Faq\Enums\FaqLevel;

class HelpController extends Controller
{
    /**
     * Display the Help / FAQ page dynamically based on user role.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Faq::with('creator')->latest();

        // 1. Tentukan rute Dashboard
        $dashboardRoute = match (true) {
            $user->hasRole('user')           => 'user.dashboard',
            $user->hasRole('admin-province') => 'admin-province.dashboard',
            $user->hasRole('admin-kab-kota') => 'admin-kab-kota.dashboard',
            default                          => 'dashboard', // admin-pusat & super-admin
        };

        // 2. Tentukan Level FAQ
        $faqLevel = match (true) {
            $user->hasRole('admin-province') => FaqLevel::PROVINSI->value,
            $user->hasRole('admin-kab-kota') => FaqLevel::KAB_KOTA->value,
            
            // Logika Region Scope khusus role 'user'
            $user->hasRole('user') => match (true) {
                $user->scopeArea?->regency_code !== null  => FaqLevel::KAB_KOTA->value,
                $user->scopeArea?->province_code !== null => FaqLevel::PROVINSI->value,
                default                                   => FaqLevel::NASIONAL->value,
            },
            
            // admin-pusat & super-admin melihat semuanya secara penuh
            $user->hasRole(['admin-pusat', 'super-admin']) => null, 
            
            // Jika role terdeteksi asing, halangi data
            default => -1, 
        };

        // 3. Terapkan filter ke Query
        if ($faqLevel !== null) {
            $faqLevel === -1 
                ? $query->where('id', -1) 
                : $query->where('level', $faqLevel);
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
