<?php

namespace Modules\Faq\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Faq\Models\Faq;
use Modules\MasterData\Models\Province;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Faq::with('creator')->latest();

        if (!$user->hasRole('admin-pusat')) {
            if ($user->hasRole('admin-province')) {
                $query->where('level', 'provinsi');
            } elseif ($user->hasRole('admin-kab-kota')) {
                $query->where('level', 'kab_kota');
            } else {
                $query->where('id', -1);
            }
        }

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

        $perPage = $request->get('per_page', 10);
        $faqs = $query->paginate($perPage)->withQueryString();

        return view('faq::index', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'level' => 'required|in:pusat,provinsi,kab_kota',
        ]);

        $user = auth()->user();

        $faq = Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'level' => $request->level,
            'created_by' => $user->id,
        ]);

        return redirect()->route('faq.index')->with('success', 'FAQ berhasil dibuat!');
    }

    public function show($id)
    {
        $faq = Faq::with('creator')->findOrFail($id);
        return view('faq::show', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     * Only Admin Pusat can update FAQs.
     */
    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'level' => 'required|in:pusat,provinsi,kab_kota',
        ]);

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'level' => $request->level,
        ]);

        return redirect()->route('faq.index')->with('success', 'FAQ berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('faq.index')->with('success', 'FAQ berhasil dihapus!');
    }
}
