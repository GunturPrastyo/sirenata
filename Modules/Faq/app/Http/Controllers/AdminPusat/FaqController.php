<?php

namespace Modules\Faq\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\Faq\Http\Requests\FaqStoreRequest;
use Modules\Faq\Http\Requests\FaqUpdateRequest;
use Modules\Faq\Models\Faq;
use Modules\Faq\Enums\FaqLevel;
use Modules\Faq\Services\FaqService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;


class FaqController extends Controller implements HasMiddleware
{
    protected string $routePrefix = 'admin-pusat.faq.';

    public function __construct(
        private FaqService $faqService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('faq-view|faq-create|faq-edit|faq-delete'), only: ['index']),
            new Middleware(PermissionMiddleware::using('faq-view'), only: ['show']),
            new Middleware(PermissionMiddleware::using('faq-create'), only: ['store']),
            new Middleware(PermissionMiddleware::using('faq-edit'), only: ['update']),
            new Middleware(PermissionMiddleware::using('faq-delete'), only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $faqs = $this->faqService->paginateFiltered(
            search: $request->search,
            level: $request->level,
            limit: $request->get('per_page', 10)
        );
        $routePrefix = $this->routePrefix;

        return view('faq::index', compact('faqs', 'routePrefix'));
    }

    public function store(FaqStoreRequest $request)
    {
        $this->faqService->createFaq($request->validated());

        return redirect()->route($this->routePrefix . 'index')->with('success', 'FAQ berhasil dibuat!');
    }

    public function show($id)
    {
        $faq = Faq::with('creator')->findOrFail($id);
        $routePrefix = $this->routePrefix;
        return view('faq::show', compact('faq', 'routePrefix'));
    }

    public function update(FaqUpdateRequest $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $this->faqService->updateFaq($faq, $request->validated());

        return redirect()->route($this->routePrefix . 'index')->with('success', 'FAQ berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $this->faqService->deleteFaq($faq);

        return redirect()->route($this->routePrefix . 'index')->with('success', 'FAQ berhasil dihapus!');
    }
}
