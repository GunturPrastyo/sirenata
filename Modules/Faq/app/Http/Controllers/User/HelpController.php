<?php

namespace Modules\Faq\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Faq\Models\Faq;
use Modules\Faq\Enums\FaqLevel;

class HelpController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Faq::with('creator')->latest();
        
        $faqLevel = match (true) {
            $user->scopeArea?->regency_code !== null  => FaqLevel::KAB_KOTA->value,
            $user->scopeArea?->province_code !== null => FaqLevel::PROVINSI->value,
            default                                   => FaqLevel::NASIONAL->value,
        };

        $query->where('level', $faqLevel);
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', '%' . $search . '%')
                    ->orWhere('answer', 'like', '%' . $search . '%');
            });
        }
        $faqs = $query->paginate(10);
        $dashboardRoute = 'user.dashboard';

        return view('faq::help.index', compact('faqs', 'dashboardRoute'));
    }
}
