<?php

namespace Modules\Faq\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\Faq\Models\Faq;

class FaqService
{
    public function paginateFiltered(
        ?string $search = null,
        ?string $level = null,
        int $limit = 10
    ): LengthAwarePaginator {
        return Faq::with('creator')
            ->latest()
            ->when($search, function ($query) use ($search) {
                $searchTerm = '%' . $search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('question', 'like', $searchTerm)
                        ->orWhere('answer', 'like', $searchTerm);
                });
            })
            ->when($level, fn($query) => $query->where('level', $level))
            ->paginate($limit)
            ->withQueryString();
    }

    public function createFaq(array $data): Faq
    {
        return Faq::create([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'level' => $data['level'],
            'created_by' => Auth::id(),
        ]);
    }

    public function updateFaq(Faq $faq, array $data): Faq
    {
        $faq->update([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'level' => $data['level'],
        ]);

        return $faq;
    }

    public function deleteFaq(Faq $faq): void
    {
        $faq->delete();
    }
}
