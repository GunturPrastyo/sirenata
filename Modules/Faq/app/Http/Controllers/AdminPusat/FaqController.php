<?php

namespace Modules\Faq\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Faq\Models\Faq;
use Modules\Faq\Enums\FaqLevel;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Faq\Exports\FaqExport;

class FaqController extends Controller
{
    protected string $routePrefix = 'admin-pusat.faq.';

    public function export(Request $request)
    {
        $filename = 'FAQ' . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new FaqExport(
                search: $request->string('search')->toString() ?: null,
                level: $request->string('level')->toString() ?: null,
            ),
            $filename
        );
    }

    public function index(Request $request)
    {
        $query = Faq::with('creator')->latest();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('question', 'like', $searchTerm)
                    ->orWhere('answer', 'like', $searchTerm);
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $faqs = $query->paginate($request->get('per_page', 10))->withQueryString();
        $routePrefix = $this->routePrefix;

        return view('faq::index', compact('faqs', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'level' => 'required|in:' . FaqLevel::NASIONAL->value . ',' . FaqLevel::PROVINSI->value . ',' . FaqLevel::KAB_KOTA->value,
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'level' => $request->level,
            'created_by' => Auth::id(),
        ]);

        ToastMagic::success('FAQ berhasil dibuat!');
        return redirect()->route($this->routePrefix . 'index');
    }

    public function show($id)
    {
        $faq = Faq::with('creator')->findOrFail($id);
        $routePrefix = $this->routePrefix;
        return view('faq::show', compact('faq', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'level' => 'required|in:' . FaqLevel::NASIONAL->value . ',' . FaqLevel::PROVINSI->value . ',' . FaqLevel::KAB_KOTA->value,
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'level' => $request->level,
        ]);

        ToastMagic::success('FAQ berhasil diperbarui!');
        return redirect()->route($this->routePrefix . 'index');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        ToastMagic::success('FAQ berhasil dihapus!');
        return redirect()->route($this->routePrefix . 'index');
    }
}
