<x-dashboard::layouts.dashboard
    title="Kursus Saya | {{ data_get($course, 'course_name') ?? data_get($course, 'name') }} | SIRENATA">
    <div class="p-2 sm:p-6 max-w-full mx-auto">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[
            ['label' => 'Kursus Saya', 'url' => route('user.course.my-course')],
            ['label' => data_get($course, 'course_name') ?? data_get($course, 'name')],
        ]" />

        {{-- Header Card --}}
        @php
            $thumbnailUrl = data_get($course, 'thumbnail_url');
            $courseName = data_get($course, 'course_name') ?? data_get($course, 'name', 'Course');
            $currentProgress = data_get($course, 'progress', 0);
            $courseSlug = data_get($course, 'slug') ?? request()->route('slug');

            if (empty($thumbnailUrl)) {
                $encodedName = urlencode($courseName);
                $thumbnailUrl = "https://ui-avatars.com/api/?name={$encodedName}&background=eff6ff&color=1e3a8a&size=512&font-size=0.33&bold=true";
            }
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 flex flex-col md:flex-row gap-6 lg:gap-8 items-start transition-all">

            {{-- Bagian Kiri: Thumbnail --}}
            <div class="w-full md:w-1/3 lg:w-1/4 shrink-0 rounded-xl overflow-hidden bg-slate-100 aspect-video md:aspect-square lg:aspect-[4/3] relative border border-slate-100">
                <img src="{{ $thumbnailUrl }}" alt="{{ $courseName }}" class="w-full h-full object-cover" />
            </div>

            {{-- Bagian Kanan: Informasi Kursus --}}
            <div class="flex-1 flex flex-col w-full h-full">
                {{-- Badges --}}
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#13416B] bg-[#13416B]/10 border border-[#13416B]/20 rounded-md">
                        {{ data_get($course, 'category.name', 'Tanpa Kategori') }}
                    </span>
                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ data_get($course, 'status') === 'completed' ? 'text-emerald-700 bg-emerald-100 border border-emerald-200' : 'text-amber-700 bg-amber-100 border border-amber-200' }} rounded-md flex items-center gap-1">
                        <i class="fas {{ data_get($course, 'status') === 'completed' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                        {{ data_get($course, 'status') === 'completed' ? 'Selesai' : 'Sedang Berjalan' }}
                    </span>
                </div>

                {{-- Judul --}}
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-3 tracking-tight">
                    {{ $courseName }}
                </h1>

                {{-- Deskripsi --}}
                <div class="prose prose-sm text-slate-600 mb-6">
                    <p class="leading-relaxed">
                        {{ data_get($course, 'description', 'Tidak ada deskripsi tersedia untuk course ini.') }}
                    </p>
                </div>

                {{-- Progress Bar --}}
                <div class="mt-auto pt-5 border-t border-slate-100 max-w-md">
                    <div class="flex justify-between items-center mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Progress Pembelajaran</span>
                        <span class="text-sm font-bold {{ $currentProgress >= 100 ? 'text-[#13416B]' : 'text-amber-600' }}">
                            {{ $currentProgress }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="{{ $currentProgress >= 100 ? 'bg-[#13416B]' : 'bg-amber-500' }} h-full rounded-full transition-all duration-700"
                            style="width: {{ $currentProgress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grid Layout (Materi & Widget Samping) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- Left column: List Modul & Evaluasi Akhir --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-slate-50 text-slate-600 rounded-lg">
                                <i class="fas fa-list-ul text-lg"></i>
                            </div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-800">Daftar Modul Pembelajaran</h2>
                        </div>
                        <span class="text-xs text-slate-500 font-bold bg-slate-100 px-3 py-1.5 rounded-md border border-slate-200">
                            {{ count(data_get($course, 'sections', [])) }} Modul
                        </span>
                    </div>

                    <div x-data="{ activeAccordion: localStorage.getItem('active_section_{{ $courseSlug }}') || 'section-0' }" x-init="$watch('activeAccordion', value => localStorage.setItem('active_section_{{ $courseSlug }}', value))" class="space-y-3">
                        @php
                            // Inisiasi Variabel Kunci: Bab pertama selalu terbuka
                            $isPreviousSectionDone = true;
                        @endphp

                        @forelse (data_get($course, 'sections', []) as $index => $section)
                            @php
                                $sectionId = data_get($section, 'id') ?? data_get($section, 'section_id');
                                $sectionName = data_get($section, 'name', 'Bagian ' . ($index + 1));
                                $sectionContentsRaw = data_get($section, 'section_contents', []);
                                
                                // Hitung materi selesai dengan logic aman (truthy)
                                $totalCount = count($sectionContentsRaw);
                                $completedCount = 0;
                                foreach ($sectionContentsRaw as $content) {
                                    if (data_get($content, 'is_completed')) {
                                        $completedCount++;
                                    }
                                }

                                $isContentCompleted = ($totalCount === 0) || ($completedCount === $totalCount);

                                // Cek keberadaan Post Test di Section ini
                                $postTestBab = $sectionId
                                    ? \Modules\LMS\Models\PostTest::where('course_section_id', $sectionId)->first()
                                    : null;

                                // Baca kelulusan evaluasi bab langsung dari DB lokal
                                $isPostTestBabCompleted = true; // Anggap true jika tak ada post test
                                if ($postTestBab) {
                                    $isPostTestBabCompleted = \Illuminate\Support\Facades\DB::table('post_test_results')
                                        ->where('user_id', auth()->id())
                                        ->where('post_test_id', $postTestBab->id)
                                        ->where('is_passed', 1) 
                                        ->exists();
                                }

                                // Section tuntas jika materi selesai & post test (jika ada) lulus
                                $isSectionCompleted = $isContentCompleted && $isPostTestBabCompleted;
                                $isLocked = !$isPreviousSectionDone;
                            @endphp

                            <div x-data="{ id: 'section-{{ $index }}', locked: {{ $isLocked ? 'true' : 'false' }} }"
                                class="border {{ $isLocked ? 'border-slate-100 bg-slate-50' : 'border-slate-200 bg-white' }} rounded-xl overflow-hidden shadow-sm transition-all duration-200"
                                :class="{ 'border-[#13416B] shadow-md ring-1 ring-[#13416B]/30': activeAccordion == id && !locked }">
                                <button @click="if(!locked) activeAccordion = (activeAccordion == id ? '' : id)"
                                    class="flex items-center justify-between w-full p-4 text-left transition-colors border-l-4 border-l-transparent {{ $isLocked ? 'cursor-not-allowed opacity-80' : 'hover:bg-slate-50/50' }}"
                                    :class="{ 'bg-slate-50/80 border-l-[#13416B]': activeAccordion == id && !locked }">
                                    <div class="flex items-start gap-4">
                                        @if ($isLocked)
                                            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-slate-200 text-slate-400 text-xs mt-0.5 shrink-0 border border-slate-300">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @elseif($isSectionCompleted)
                                            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-emerald-50 text-emerald-600 text-xs mt-0.5 shrink-0 border border-emerald-200">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        @else
                                            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-slate-600 text-xs font-bold mt-0.5 shrink-0 border border-slate-200">
                                                {{ $index + 1 }}
                                            </span>
                                        @endif

                                        <div>
                                            <h3 class="font-bold {{ $isLocked ? 'text-slate-500' : 'text-slate-800' }} text-sm sm:text-base leading-snug">
                                                {{ $sectionName }}
                                            </h3>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                                    <i class="far fa-file-alt text-slate-400"></i> {{ $totalCount }} Materi
                                                </span>
                                                @if ($postTestBab)
                                                    <span class="text-[10px] text-slate-300">•</span>
                                                    <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                                        <i class="fas fa-clipboard-list text-slate-400"></i> 1 Evaluasi
                                                    </span>
                                                @endif
                                                <span class="text-[10px] text-slate-300">•</span>

                                                @if ($isLocked)
                                                    <span class="text-xs font-semibold text-slate-400">Terkunci</span>
                                                @else
                                                    <span class="text-xs font-semibold {{ $isSectionCompleted ? 'text-[#13416B]' : 'text-amber-600' }}">
                                                        {{ $completedCount }}/{{ $totalCount }} Selesai
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 {{ $isLocked ? 'text-slate-300' : 'text-slate-400' }} transition-transform duration-200 shrink-0 ml-2"
                                        :class="{ 'rotate-180 text-[#13416B]': activeAccordion == id && !locked }"
                                        viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2.5">
                                        <path d="M6 9l6 6 6-6" />
                                    </svg>
                                </button>

                                <div x-show="activeAccordion == id && !locked" x-collapse x-cloak>
                                    <div class="p-4 pt-2 border-t border-slate-100 bg-slate-50/30 space-y-3">
                                        {{-- Looping Materi --}}
                                        @forelse ($sectionContentsRaw as $content)
                                            @php
                                                $contentId = data_get($content, 'id', Str::random(5));
                                                $contentName = data_get($content, 'name', 'Materi Tanpa Judul');
                                                $videoUrlRaw = data_get($content, 'video_url');
                                                $documentUrlRaw = data_get($content, 'document_url');
                                                $isContentItemCompleted = data_get($content, 'is_completed', false);
                                            @endphp
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-xl border border-slate-200 bg-white hover:border-[#13416B]/40 hover:shadow-sm transition-all duration-200 gap-4">
                                                <div class="flex items-center gap-4">
                                                    @if (!empty($videoUrlRaw))
                                                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-50 text-blue-600 shrink-0 border border-blue-100">
                                                            <i class="fas fa-play text-sm"></i>
                                                        </span>
                                                    @elseif(!empty($documentUrlRaw))
                                                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-50 text-blue-600 shrink-0 border border-blue-100">
                                                            <i class="fas fa-file-pdf text-lg"></i>
                                                        </span>
                                                    @else
                                                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-50 text-blue-600 shrink-0 border border-blue-100">
                                                            <i class="fas fa-file-alt text-lg"></i>
                                                        </span>
                                                    @endif
                                                    <div>
                                                        <p class="font-bold text-slate-800 text-sm leading-tight">
                                                            {{ $contentName }}
                                                        </p>
                                                        <div class="mt-1.5 flex items-center gap-1.5">
                                                            @if ($isContentItemCompleted)
                                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#13416B] bg-[#13416B]/10 px-2 py-0.5 rounded-md border border-[#13416B]/20">
                                                                    <i class="fas fa-check"></i> Selesai
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                                                                    Belum Selesai
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tombol Pelajari Materi --}}
                                                <a href="{{ route('user.course.content.show', ['slug' => $courseSlug, 'content' => $contentId]) }}"
                                                    class="sm:self-center px-5 py-2 {{ $isContentItemCompleted ? 'bg-white border border-[#13416B]/30 text-[#13416B] hover:bg-[#13416B]/10' : 'bg-[#13416B] hover:bg-[#0f3354] text-white shadow-sm' }} rounded-xl text-xs font-bold transition-all text-center shrink-0 flex items-center justify-center gap-2">
                                                    <i class="fas {{ $isContentItemCompleted ? 'fa-eye' : 'fa-play' }}"></i>
                                                    {{ $isContentItemCompleted ? 'Lihat Kembali' : 'Pelajari Materi' }}
                                                </a>
                                            </div>
                                        @empty
                                            <div class="p-4 text-center text-slate-500 text-sm bg-white rounded-xl border border-dashed border-slate-200">
                                                <i class="fas fa-folder-open mb-2 text-slate-300 text-xl block"></i>
                                                Materi sedang disiapkan.
                                            </div>
                                        @endforelse

                                        {{-- Post Test per Bab --}}
                                        @if ($postTestBab)
                                            <div class="mt-4 pt-4 border-t border-slate-200/80">
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-xl border border-amber-200 bg-amber-50 hover:shadow-sm transition-all gap-4">
                                                    <div class="flex items-center gap-4">
                                                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100 text-amber-600 shrink-0 border border-amber-200">
                                                            <i class="fas fa-clipboard-check text-lg"></i>
                                                        </span>
                                                        <div>
                                                            <p class="font-bold text-slate-800 text-sm leading-tight">
                                                                {{ data_get($postTestBab, 'title', 'Post Test: ' . $sectionName) }}
                                                            </p>
                                                            @if (data_get($postTestBab, 'description'))
                                                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                                                    {{ data_get($postTestBab, 'description') }}
                                                                </p>
                                                            @endif
                                                            <div class="mt-1.5 flex items-center gap-1.5">
                                                                @if ($isPostTestBabCompleted)
                                                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                                                        <i class="fas fa-check"></i> Selesai
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center text-[10px] font-bold text-amber-700 bg-white px-2 py-0.5 rounded-md border border-amber-200">
                                                                        Wajib Dikerjakan
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Tombol Kerjakan Evaluasi --}}
                                                    <a href="{{ route('user.course.test.show', ['slug' => $courseSlug, 'postTestId' => $postTestBab->id]) }}"
                                                        class="sm:self-center px-5 py-2.5 {{ $isPostTestBabCompleted ? 'bg-white border border-[#13416B]/30 text-[#13416B] hover:bg-[#13416B]/10' : 'bg-[#13416B] hover:bg-[#0f3354] text-white shadow-sm' }} rounded-xl text-xs font-bold transition-all text-center shrink-0 flex items-center justify-center gap-2">
                                                        <i class="fas {{ $isPostTestBabCompleted ? 'fa-eye' : 'fa-pencil-alt' }}"></i>
                                                        {{ $isPostTestBabCompleted ? 'Lihat Hasil' : 'Kerjakan Evaluasi' }}
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @php
                                // Update flag kunci untuk iterasi bab selanjutnya
                                $isPreviousSectionDone = $isSectionCompleted;
                            @endphp
                        @empty
                            <div class="p-8 text-center text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                <i class="fas fa-layer-group mb-3 text-slate-300 text-3xl block"></i>
                                <p class="font-semibold text-sm">Belum ada modul pembelajaran.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- CARD EVALUASI AKHIR --}}
                @php
                    $courseId = data_get($course, 'id') ?? data_get($course, 'course_id');
                    $evaluasiAkhir = $courseId
                        ? \Modules\LMS\Models\PostTest::where('course_id', $courseId)
                            ->whereNull('course_section_id')
                            ->first()
                        : null;

                    $isEvaluasiAkhirCompleted = false;
                    if ($evaluasiAkhir) {
                        $isEvaluasiAkhirCompleted = \Illuminate\Support\Facades\DB::table('post_test_results')
                            ->where('user_id', auth()->id())
                            ->where('post_test_id', $evaluasiAkhir->id)
                            ->where('is_passed', 1)
                            ->exists();
                    }

                    $isEvaluasiAkhirLocked = !$isPreviousSectionDone;
                @endphp

                @if ($evaluasiAkhir)
                    <div class="bg-white rounded-2xl shadow-sm border {{ $isEvaluasiAkhirLocked ? 'border-slate-200 opacity-90' : 'border-[#13416B]/60 ring-2 ring-[#13416B]/20' }} overflow-hidden transition-all relative">
                        <div class="px-6 py-5 {{ $isEvaluasiAkhirLocked ? 'bg-slate-50 border-b border-slate-200' : 'bg-gradient-to-r from-[#13416B] to-[#0f3354] text-white' }} flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ $isEvaluasiAkhirLocked ? 'bg-slate-200 text-slate-400' : 'bg-white/20 text-white border border-white/30' }}">
                                    <i class="fas {{ $isEvaluasiAkhirLocked ? 'fa-lock' : 'fa-graduation-cap' }} text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider {{ $isEvaluasiAkhirLocked ? 'text-slate-400' : 'text-blue-100' }} mb-1">
                                        Tahap Akhir
                                    </p>
                                    <h3 class="text-lg font-extrabold {{ $isEvaluasiAkhirLocked ? 'text-slate-800' : 'text-white' }} leading-tight">
                                        {{ data_get($evaluasiAkhir, 'title', 'Evaluasi Akhir Course') }}
                                    </h3>
                                </div>
                            </div>

                            @if ($isEvaluasiAkhirLocked)
                                <span class="px-4 py-2 text-xs font-bold text-slate-500 bg-white border border-slate-200 rounded-xl text-center shadow-sm">
                                    Selesaikan Modul Dahulu
                                </span>
                            @elseif($isEvaluasiAkhirCompleted)
                                <a href="{{ route('user.course.test.show', ['slug' => $courseSlug, 'postTestId' => $evaluasiAkhir->id]) }}" 
                                    class="px-5 py-2.5 text-sm font-bold text-[#13416B] bg-white hover:bg-slate-50 rounded-xl text-center shadow-sm transition-all flex items-center justify-center gap-2 border border-transparent">
                                    <i class="fas fa-eye"></i> Lihat Hasil
                                </a>
                            @else
                                <a href="{{ route('user.course.test.show', ['slug' => $courseSlug, 'postTestId' => $evaluasiAkhir->id]) }}"
                                    class="px-5 py-2.5 text-sm font-bold text-[#13416B] bg-white hover:bg-slate-50 rounded-xl text-center shadow-sm transition-all flex items-center justify-center gap-2 border border-transparent">
                                    <i class="fas fa-play"></i> Mulai Evaluasi
                                </a>
                            @endif
                        </div>

                        <div class="p-6 bg-white">
                            <p class="text-sm {{ $isEvaluasiAkhirLocked ? 'text-slate-500' : 'text-slate-600' }} leading-relaxed">
                                {{ data_get($evaluasiAkhir, 'description', 'Ujian utama ini adalah syarat mutlak penyelesaian kursus. Pastikan Anda telah menguasai seluruh materi sebelum memulai. Nilai dari evaluasi ini akan menentukan kelayakan Anda untuk mendapatkan sertifikat kelulusan.') }}
                            </p>

                            <div class="mt-5 flex flex-wrap items-center gap-3">
                                <span class="text-xs font-semibold text-slate-600 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 flex items-center gap-1.5">
                                    <i class="fas fa-list-ol text-slate-400"></i>
                                    {{ data_get($evaluasiAkhir, 'questions', collect())->count() ?? 0 }} Soal
                                </span>
                                <span class="text-xs font-semibold text-slate-600 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 flex items-center gap-1.5">
                                    <i class="fas fa-stopwatch text-slate-400"></i>
                                    {{ data_get($evaluasiAkhir, 'duration', 0) }} Menit
                                </span>
                                <span class="text-xs font-semibold text-slate-600 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 flex items-center gap-1.5">
                                    <i class="fas fa-bullseye text-slate-400"></i> KKM:
                                    {{ data_get($evaluasiAkhir, 'passing_score', 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column: Side Widget --}}
            <div class="space-y-6 lg:sticky lg:top-24 lg:self-start">

                {{-- Course Stats Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sm:p-6">
                    <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                        <div class="p-2 bg-[#13416B]/10 text-[#13416B] border border-[#13416B]/20 rounded-lg shrink-0">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Statistik Belajar</h3>
                    </div>

                    @php
                        $totalContents = collect(data_get($course, 'sections', []))->sum(
                            fn($s) => count(data_get($s, 'section_contents', [])),
                        );
                    @endphp

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                            <span class="text-[10px] uppercase font-bold text-slate-500 mb-2 flex items-center gap-1"><i class="fas fa-info-circle"></i> Status</span>
                            <span class="inline-flex items-center justify-center px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ data_get($course, 'status') === 'completed' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-amber-100 text-amber-700 border border-amber-200' }}">
                                {{ data_get($course, 'status') === 'completed' ? 'Selesai' : 'Berjalan' }}
                            </span>
                        </div>
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                            <span class="text-[10px] uppercase font-bold text-slate-500 mb-1 flex items-center gap-1"><i class="fas fa-layer-group"></i> Modul</span>
                            <span class="text-sm font-extrabold text-slate-800">{{ count(data_get($course, 'sections', [])) }}</span>
                        </div>
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                            <span class="text-[10px] uppercase font-bold text-slate-500 mb-1 flex items-center gap-1"><i class="fas fa-file-alt"></i> Materi</span>
                            <span class="text-sm font-extrabold text-slate-800">{{ $totalContents }}</span>
                        </div>
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 flex flex-col justify-between">
                            <span class="text-[10px] uppercase font-bold text-slate-500 mb-1 flex items-center gap-1"><i class="fas fa-check-double"></i> Selesai</span>
                            <span class="text-sm font-extrabold text-[#13416B]">{{ data_get($course, 'completed_count', 0) }}
                                <span class="text-xs text-slate-400 font-medium">/ {{ $totalContents }}</span></span>
                        </div>
                    </div>
                </div>

                {{-- Certificate Widget --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sm:p-6">
                    <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                        <div class="p-2 bg-[#13416B]/10 text-[#13416B] border border-[#13416B]/20 rounded-lg shrink-0">
                            <i class="fas fa-certificate text-lg"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Sertifikat Kelulusan</h3>
                    </div>

                    @if (data_get($course, 'status') === 'completed' || data_get($course, 'progress', 0) >= 100)
                        @if (!empty(data_get($course, 'certificate_file')))
                            <div class="space-y-5">
                                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 text-center">
                                    <div class="w-12 h-12 bg-white text-emerald-600 rounded-full shadow-sm flex items-center justify-center mx-auto mb-3 text-xl border border-emerald-100">
                                        <i class="fas fa-award"></i>
                                    </div>
                                    <p class="font-bold text-emerald-800 text-sm">Sertifikat Tersedia</p>
                                    <p class="text-xs text-emerald-600 mt-1">Selamat! Anda telah menyelesaikan kursus.</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-3 text-xs">
                                    <div class="flex justify-between border-b border-slate-200 pb-2">
                                        <span class="text-slate-500 font-semibold">Nomor</span>
                                        <span class="font-mono font-bold text-slate-800">{{ data_get($course, 'certificate_code') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 font-semibold">Tgl Terbit</span>
                                        <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse(data_get($course, 'certificate_issued_at'))->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>
                                <a href="{{ data_get($course, 'certificate_file') }}" target="_blank" download
                                    class="w-full bg-[#13416B] hover:bg-[#0f3354] text-white py-3 rounded-xl font-bold transition-all shadow-sm flex items-center justify-center gap-2 text-sm">
                                    <i class="fas fa-download"></i> Unduh PDF
                                </a>
                            </div>
                        @else
                            <div class="space-y-5">
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-center">
                                    <div class="w-12 h-12 bg-white text-amber-500 rounded-full shadow-sm flex items-center justify-center mx-auto mb-3 text-xl border border-amber-100">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <p class="font-bold text-amber-800 text-sm">Kursus Selesai!</p>
                                    <p class="text-xs text-amber-600 mt-1">Silakan terbitkan sertifikat Anda.</p>
                                </div>
                                <form action="{{ route('user.course.my-course.generate-certificate', ['slug' => request()->route('slug')]) }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                                    @csrf
                                    <button type="submit" :disabled="loading"
                                        class="w-full bg-[#13416B] hover:bg-[#0f3354] text-white py-3 rounded-xl font-bold transition-all shadow-sm flex items-center justify-center gap-2 text-sm disabled:opacity-70 disabled:cursor-not-allowed">
                                        <template x-if="!loading">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-cogs"></i> Terbitkan Sertifikat
                                            </span>
                                        </template>
                                        <template x-if="loading">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-spinner fa-spin"></i> Memproses...
                                            </span>
                                        </template>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="py-6 flex flex-col items-center justify-center text-center px-2">
                            <div class="w-16 h-16 bg-slate-50 border border-slate-200 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-lock text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-800 mb-1.5">Sertifikat Terkunci</p>

                            @php
                                $remaining = $totalContents - data_get($course, 'completed_count', 0);
                            @endphp

                            @if ($remaining > 0)
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Selesaikan <span class="font-bold text-slate-800">{{ $remaining }} materi</span> dan evaluasi akhir untuk membuka sertifikat kelulusan.
                                </p>
                            @else
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Selesaikan seluruh tahapan hingga progress mencapai 100% untuk mengunduh sertifikat.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>