@props(['faqs', 'backRoute'])

<div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto min-h-screen">
    
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <x-breadcrumb :home="$backRoute" :items="[['label' => 'Bantuan']]" />
    </div>

    {{-- Search Section --}}
    <div class="mb-6 relative">
        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
        <input id="searchFaq" type="text" placeholder="Cari bantuan atau pertanyaan..." 
               class="w-full bg-white border border-slate-200 shadow-sm rounded-lg pl-11 pr-4 py-3 sm:py-3.5 text-sm sm:text-base focus:ring-[#13416B] focus:border-[#13416B] transition-colors text-slate-800 placeholder-slate-400">
    </div>

    {{-- Contact Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-8">
        <!-- Card FAQ -->
        <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center gap-4 sm:gap-5 hover:border-[#13416B]/30 hover:shadow transition-all group cursor-default">
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#13416B]/5 text-[#13416B] rounded-lg flex items-center justify-center shrink-0 border border-[#13416B]/10 group-hover:bg-[#13416B]/10 transition-colors">
                <i class="fas fa-info-circle text-xl sm:text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm sm:text-base mb-0.5">FAQ</h3>
                <p class="text-xs sm:text-sm text-slate-500">Pertanyaan yang sering ditanyakan</p>
            </div>
        </div>
        
        <!-- Card Email Support -->
        <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center gap-4 sm:gap-5 hover:border-amber-300 hover:shadow transition-all group">
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center shrink-0 border border-amber-100 group-hover:bg-amber-100 transition-colors">
                <i class="fas fa-envelope text-xl sm:text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm sm:text-base mb-0.5">Email Support</h3>
                <a href="mailto:support@sirenata.go.id" class="text-xs sm:text-sm font-semibold text-amber-600 hover:text-amber-800 transition-colors">support@sirenata.go.id</a>
            </div>
        </div>
    </div>

    {{-- FAQ Section --}}
    <div class="bg-white rounded-lg p-5 sm:p-8 shadow-sm border border-slate-200">
        <h2 class="text-lg sm:text-xl font-bold text-slate-800 mb-5 sm:mb-6 border-b border-slate-100 pb-4">
            Pertanyaan Umum
        </h2>
        
        <div class="space-y-3" id="faqList">
            @forelse($faqs as $faq)
                <div class="faq-item border border-slate-200 rounded-lg overflow-hidden transition-all duration-200 bg-white"
                     data-question="{{ strtolower($faq->question) }}"
                     data-answer="{{ strtolower($faq->answer) }}"
                     x-data="{ expanded: false }"
                     :class="expanded ? 'border-[#13416B]/30 ring-1 ring-[#13416B]/10' : 'hover:border-slate-300'">
                     
                    <button @click="expanded = !expanded" class="w-full px-4 py-3.5 sm:px-5 sm:py-4 flex items-start sm:items-center justify-between text-left focus:outline-none gap-4 bg-slate-50/50 hover:bg-slate-50 transition-colors" :class="expanded ? 'bg-slate-50' : ''">
                        <span class="font-semibold text-sm text-slate-700 transition-colors" :class="expanded ? 'text-[#13416B]' : ''">
                            {{ $faq->question }}
                        </span>
                        <div class="w-6 h-6 rounded flex items-center justify-center shrink-0 transition-colors mt-0.5 sm:mt-0" :class="expanded ? 'bg-[#13416B]/10 text-[#13416B]' : 'text-slate-400'">
                            <i class="fas fa-chevron-down transition-transform duration-200 text-[10px] sm:text-xs" :class="expanded ? 'rotate-180' : ''"></i>
                        </div>
                    </button>
                    
                    <div x-show="expanded" x-collapse>
                        <div class="px-4 pb-4 sm:px-5 sm:pb-5 pt-2 text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-white">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10" id="emptyState">
                    <div class="w-16 h-16 bg-slate-50 rounded-lg flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <i class="fas fa-box-open text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-700">Belum ada FAQ tersedia.</p>
                    <p class="text-xs text-slate-500 mt-1">Panduan akan ditambahkan segera oleh tim admin.</p>
                </div>
            @endforelse
        </div>

        {{-- No Results State --}}
        <div class="hidden text-center py-10" id="noResults">
            <div class="w-16 h-16 bg-slate-50 rounded-lg flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <i class="fas fa-search text-2xl text-slate-300"></i>
            </div>
            <p class="text-sm font-bold text-slate-700">Tidak ditemukan hasil.</p>
            <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci pencarian lain.</p>
        </div>

        {{-- Pagination --}}
        @if($faqs->hasPages())
            <div class="mt-6 pt-5 border-t border-slate-100">
                {{ $faqs->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
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