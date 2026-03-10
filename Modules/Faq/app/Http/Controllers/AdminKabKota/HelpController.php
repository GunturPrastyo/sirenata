<?php

namespace Modules\Faq\Http\Controllers\AdminKabKota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Faq\Models\Faq;
use Modules\Faq\Enums\FaqLevel;

class HelpController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::with('creator')->latest();
        $query->where('level', FaqLevel::KAB_KOTA->value);
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', '%' . $search . '%')
                    ->orWhere('answer', 'like', '%' . $search . '%');
            });
        }
        $faqs = $query->paginate(10);
        $dashboardRoute = 'admin-kab-kota.dashboard';

        return view('faq::help.index', compact('faqs', 'dashboardRoute'));
    }
}
