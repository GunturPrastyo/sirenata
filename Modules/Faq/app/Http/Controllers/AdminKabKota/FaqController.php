<?php

namespace Modules\Faq\Http\Controllers\AdminKabKota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Faq\Models\Faq;
use Modules\Faq\Enums\FaqLevel;

class FaqController extends Controller
{
    protected string $routePrefix = 'admin-kab-kota.faq.';

    public function index(Request $request)
    {
        $query = Faq::with('creator')->latest();
        $query->where('level', FaqLevel::KAB_KOTA->value);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('question', 'like', $searchTerm)
                    ->orWhere('answer', 'like', $searchTerm);
            });
        }

        $faqs = $query->paginate($request->get('per_page', 10))->withQueryString();
        $routePrefix = $this->routePrefix;

        return view('faq::index', compact('faqs', 'routePrefix'));
    }
}
