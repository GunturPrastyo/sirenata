@props(['faqs', 'backRoute'])

<div class="p-2 sm:p-6">

    <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1">
            <li class="inline-flex items-center">
                <a href="{{ $backRoute }}"
                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                        </path>
                    </svg>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Bantuan</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mb-6">
        <input type="text" id="searchFaq" placeholder="Cari bantuan..."
            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors shadow-sm">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div
            class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer group">
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">FAQ</h3>
            <p class="text-sm text-gray-600">Pertanyaan yang sering ditanyakan</p>
        </div>
        <div
            class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer group">
            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Email Support</h3>
            <p class="text-sm text-gray-600">support@sirenata.go.id</p>
        </div>
    </div>

    <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm border border-gray-200">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Pertanyaan Umum</h2>
        <div class="space-y-4" id="faqList">
            @forelse($faqs as $faq)
                <details class="border border-gray-200 rounded-lg faq-item" data-question="{{ strtolower($faq->question) }}"
                    data-answer="{{ strtolower($faq->answer) }}">
                    <summary class="px-4 py-3 cursor-pointer font-medium text-gray-900 hover:bg-gray-50">
                        {{ $faq->question }}
                    </summary>
                    <div class="px-4 py-3 text-sm text-gray-600 border-t border-gray-200">
                        {!! nl2br(e($faq->answer)) !!}
                    </div>
                </details>
            @empty
                <div class="text-center py-8" id="emptyState">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada FAQ tersedia.</p>
                    <p class="text-sm text-gray-400 mt-1">Hubungi admin jika Anda memerlukan bantuan.</p>
                </div>
            @endforelse
        </div>

        <div class="hidden text-center py-8" id="noResults">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <p class="text-gray-500 font-medium">Tidak ditemukan FAQ yang cocok.</p>
            <p class="text-sm text-gray-400 mt-1">Coba gunakan kata kunci lain.</p>
        </div>

        @if($faqs->hasPages())
            <div class="mt-6 pt-4 border-t border-gray-200">
                {{ $faqs->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        // FAQ search filter
        document.getElementById('searchFaq')?.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const faqItems = document.querySelectorAll('.faq-item');
            const noResults = document.getElementById('noResults');
            let visibleCount = 0;

            faqItems.forEach(function (item) {
                const question = item.getAttribute('data-question') || '';
                const answer = item.getAttribute('data-answer') || '';

                if (question.includes(query) || answer.includes(query)) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (faqItems.length > 0) {
                noResults.classList.toggle('hidden', visibleCount > 0);
            }
        });
    </script>
@endpush