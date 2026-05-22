<x-dashboard::layouts.dashboard title="Kursus Saya | {{ $course->course_name }} | SIRENATA">
    <div class="p-2 sm:p-6 max-w-7xl mx-auto">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[['label' => 'Kursus Saya', 'url' => route('user.course.my-course')], ['label' => $course->course_name]]" />

        {{-- Header Status Banner --}}
        <div class="relative overflow-hidden bg-slate-900 rounded-2xl p-5 sm:p-6 text-white mb-6 border border-indigo-500/20 shadow-md">
            <div class="absolute top-0 right-0 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-60 h-60 bg-blue-500/10 rounded-full blur-2xl -ml-20 -mb-20"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-2 max-w-2xl">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $course->status === 'completed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' }}">
                        {{ $course->status === 'completed' ? 'Selesai' : 'Sedang Berjalan' }}
                    </span>
                    <h1 class="text-lg sm:text-xl font-bold tracking-tight text-white leading-snug">
                        {{ $course->course_name }}
                    </h1>
                </div>
                
                <div class="flex items-center gap-3 shrink-0 bg-white/5 border border-white/10 rounded-xl p-3 backdrop-blur-sm">
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Progress Belajar</p>
                        <p class="text-lg sm:text-xl font-extrabold text-indigo-300">{{ $course->progress }}%</p>
                    </div>
                    <div class="w-10 h-10 rounded-full border-2 border-indigo-500/20 flex items-center justify-center relative overflow-hidden shrink-0">
                        <div class="absolute inset-x-0 bottom-0 bg-indigo-500 opacity-30 w-full" style="height: {{ $course->progress }}%"></div>
                        <svg class="w-5 h-5 text-indigo-400 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 w-full bg-slate-800/80 rounded-full h-2 border border-slate-700/50">
                <div class="bg-indigo-600 h-full rounded-full transition-all duration-500 shadow-[0_0_8px_rgba(99,102,241,0.5)]" style="width: {{ $course->progress }}%"></div>
            </div>
        </div>

        {{-- Grid Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            {{-- Left column: List Modul --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📚</span>
                            <h2 class="text-base font-bold text-slate-850">Daftar Modul</h2>
                        </div>
                        <span class="text-[11px] text-slate-500 font-bold bg-slate-100 px-2.5 py-1 rounded-full">
                            Total: {{ count($course->sections ?? []) }} Modul
                        </span>
                    </div>

                    <div 
                        x-data="{ activeAccordion: localStorage.getItem('active_section_{{ request()->route('slug') }}') || 'section-0' }"
                        x-init="$watch('activeAccordion', value => localStorage.setItem('active_section_{{ request()->route('slug') }}', value))"
                        class="space-y-3"
                    >
                        @forelse ($course->sections ?? [] as $index => $section)
                            @php
                                $section = (object) $section;
                                $sectionContents = collect($section->section_contents ?? []);
                                $completedCount = $sectionContents->where('is_completed', true)->count();
                                $totalCount = $sectionContents->count();
                                $isSectionCompleted = $totalCount > 0 && $completedCount === $totalCount;
                            @endphp

                            <div 
                                x-data="{ id: 'section-{{ $index }}' }" 
                                class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm transition-all duration-200"
                                :class="{ 'border-indigo-500 shadow-md ring-1 ring-indigo-500/10': activeAccordion == id }"
                            >
                                <button
                                    @click="activeAccordion = (activeAccordion == id ? '' : id)"
                                    class="flex items-center justify-between w-full p-4 text-left hover:bg-slate-50/50 transition-colors border-l-4 border-l-transparent"
                                    :class="{ 'bg-slate-50/80 border-l-indigo-600': activeAccordion == id }"
                                >
                                    <div class="flex items-start gap-3">
                                        @if($isSectionCompleted)
                                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 text-xs font-extrabold mt-0.5 shrink-0 border border-emerald-100">
                                                ✓
                                            </span>
                                        @else
                                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-50 text-slate-600 text-xs font-bold mt-0.5 shrink-0 border border-slate-150">
                                                {{ $index + 1 }}
                                            </span>
                                        @endif
                                        <div>
                                            <h3 class="font-bold text-slate-800 text-sm sm:text-base leading-snug">
                                                {{ $section->name }}
                                            </h3>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-xs text-slate-400 font-medium">
                                                    {{ $totalCount }} Materi
                                                </span>
                                                <span class="text-[10px] text-slate-300">•</span>
                                                <span class="text-xs font-semibold {{ $isSectionCompleted ? 'text-emerald-600' : 'text-indigo-600' }}">
                                                    {{ $completedCount }}/{{ $totalCount }} Selesai
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <svg
                                        class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0 ml-2"
                                        :class="{ 'rotate-180 text-indigo-600': activeAccordion == id }"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        fill="none"
                                        stroke-width="2.5"
                                    >
                                        <path d="M6 9l6 6 6-6" />
                                    </svg>
                                </button>

                                <div x-show="activeAccordion == id" x-collapse x-cloak>
                                    <div class="p-4 pt-2 border-t border-slate-100 bg-slate-50/20 space-y-2.5">
                                        @forelse ($section->section_contents ?? [] as $content)
                                            @php
                                                $content = (object) $content;
                                            @endphp

                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 rounded-xl border border-slate-150 bg-white hover:border-slate-350 hover:shadow-sm transition-all duration-200 gap-3">
                                                <div class="flex items-center gap-3">
                                                    @if(!empty($content->video_url))
                                                        <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-blue-50 text-blue-600 shrink-0 border border-blue-100/50">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                                            </svg>
                                                        </span>
                                                    @elseif(!empty($content->document_url))
                                                        <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-rose-50 text-rose-600 shrink-0 border border-rose-100/50">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A1 1 0 0112 2.586L15.414 6A1 1 0 0116 6.586V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </span>
                                                    @else
                                                        <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-amber-50 text-amber-600 shrink-0 border border-amber-100/50">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    @endif
                                                    <div>
                                                        <p class="font-bold text-slate-800 text-sm leading-tight">
                                                            {{ $content->name }}
                                                        </p>
                                                        <div class="mt-1 flex items-center gap-1.5">
                                                            @if ($content->is_completed)
                                                                <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                                                    Selesai ✓
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">
                                                                    Belum Selesai
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                @php
                                                    $embedUrl = null;
                                                    $url = $content->video_url ?? null;

                                                    if ($url) {
                                                        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
                                                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                                        } elseif (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
                                                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                                        } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
                                                            $embedUrl = $url;
                                                        } else {
                                                            $embedUrl = $url;
                                                        }
                                                    }
                                                @endphp

                                                @if ($content->is_completed)
                                                    <button
                                                        type="button"
                                                        @click="$dispatch('open-modal', 'show-content-{{ $content->id }}')"
                                                        class="sm:self-center px-3.5 py-1.5 border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 rounded-xl text-xs font-semibold transition-colors text-center shrink-0"
                                                    >
                                                        Lihat Kembali
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        @click="$dispatch('open-modal', 'show-content-{{ $content->id }}')"
                                                        class="sm:self-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all hover:shadow-sm text-center shrink-0"
                                                    >
                                                        Mulai Belajar
                                                    </button>
                                                @endif

                                                {{-- Modal --}}
                                                <x-modal
                                                    name="show-content-{{ $content->id }}"
                                                    title="Lihat Materi"
                                                    maxWidth="sm:max-w-3xl"
                                                >
                                                    <div
                                                        x-data="{ videoUrl: '{{ $embedUrl }}' }"
                                                        x-on:close-modal.window="if ($event.detail.name === 'show-content-{{ $content->id }}') { 
                                                            let tempUrl = videoUrl; 
                                                            videoUrl = ''; 
                                                            setTimeout(() => videoUrl = tempUrl, 100); 
                                                        }"
                                                    >
                                                        {{-- Header --}}
                                                        <div
                                                            class="px-6 py-4 border-b border-slate-100 flex items-start justify-between gap-3"
                                                        >
                                                            <div>
                                                                <p
                                                                    class="text-xs text-slate-400 mb-0.5"
                                                                >
                                                                    Materi
                                                                </p>
                                                                <h2
                                                                    class="text-base font-bold text-slate-800 leading-tight"
                                                                >
                                                                    {{ $content->name }}
                                                                </h2>
                                                            </div>
                                                        </div>

                                                        <div class="p-6 space-y-5">
                                                            {{-- Video Section --}}
                                                            @if ($embedUrl)
                                                                <div>
                                                                    <p
                                                                        class="text-xs font-medium text-slate-500 mb-2 flex items-center gap-1.5"
                                                                    >
                                                                        🎥 Video Materi
                                                                    </p>
                                                                    <div
                                                                        class="aspect-video w-full rounded-xl overflow-hidden shadow-sm border border-slate-200 bg-black"
                                                                    >
                                                                        <iframe
                                                                            class="w-full h-full"
                                                                            x-bind:src="videoUrl"
                                                                            frameborder="0"
                                                                            allow="
                                                                                accelerometer;
                                                                                autoplay;
                                                                                clipboard-write;
                                                                                encrypted-media;
                                                                                gyroscope;
                                                                                picture-in-picture;
                                                                            "
                                                                            allowfullscreen
                                                                        ></iframe>
                                                                    </div>
                                                                    <div class="mt-2 flex justify-end">
                                                                        <a
                                                                            href="{{ $content->video_url }}"
                                                                            target="_blank"
                                                                            class="inline-flex items-center gap-1 text-xs text-slate-400 hover:text-indigo-600 transition-colors"
                                                                        >
                                                                            Buka di YouTube
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            {{-- Document Section --}}
                                                            @if ($content->document_url)
                                                                <div>
                                                                    <p
                                                                        class="text-xs font-medium text-slate-500 mb-2 flex items-center gap-1.5"
                                                                    >
                                                                        📄 Dokumen Pendukung
                                                                    </p>
                                                                    <div
                                                                        class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between hover:border-indigo-200 transition-colors"
                                                                    >
                                                                        <div
                                                                            class="flex items-center gap-3"
                                                                        >
                                                                            <div
                                                                                class="p-2.5 bg-white rounded-lg border border-slate-100 shadow-sm shrink-0"
                                                                            >
                                                                                <svg
                                                                                    class="w-5 h-5 text-red-500"
                                                                                    fill="currentColor"
                                                                                    viewBox="0 0 24 24"
                                                                                >
                                                                                    <path
                                                                                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"
                                                                                    />
                                                                                </svg>
                                                                            </div>
                                                                            <div>
                                                                                <p
                                                                                    class="text-sm font-medium text-slate-700"
                                                                                >
                                                                                    Dokumen
                                                                                </p>
                                                                                <p
                                                                                    class="text-xs text-slate-400"
                                                                                >
                                                                                    Klik untuk membuka dokumen
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <a
                                                                            href="{{ $content->document_url }}"
                                                                            target="_blank"
                                                                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition-colors shrink-0"
                                                                        >
                                                                            Buka
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            {{-- Empty State --}}
                                                            @if (! $embedUrl && ! $content->document_url)
                                                                <div
                                                                    class="text-center py-10 text-slate-400"
                                                                >
                                                                    <p class="text-sm font-medium">
                                                                        Belum ada media
                                                                    </p>
                                                                    <p class="text-xs mt-1">
                                                                        Materi ini belum memiliki video atau dokumen pendukung.
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        {{-- Footer --}}
                                                        <div
                                                            class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end items-center gap-3"
                                                        >
                                                            @if (!$content->is_completed)
                                                                <form action="{{ route('user.course.content.complete', ['content' => $content->id]) }}" method="POST" class="m-0">
                                                                    @csrf
                                                                    <button
                                                                        type="submit"
                                                                        class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-1.5"
                                                                    >
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                        </svg>
                                                                        Tandai Selesai
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="inline-flex items-center text-sm font-semibold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">
                                                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    Materi Selesai ✓
                                                                </span>
                                                            @endif

                                                            <button
                                                                type="button"
                                                                @click="$dispatch('close-modal', 'show-content-{{ $content->id }}')"
                                                                class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors"
                                                            >
                                                                Tutup
                                                            </button>
                                                        </div>
                                                    </div>
                                                </x-modal>
                                            </div>
                                        @empty
                                            <p class="text-slate-400 py-3 text-sm italic">Belum ada materi di modul ini.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="p-4 text-slate-400 text-center">Belum ada modul.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Column: Side Widget (Sticky on desktop) --}}
            <div class="space-y-6 lg:sticky lg:top-[72px] lg:self-start">
                
                {{-- Course Stats Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 sm:p-6">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                        <span class="text-base">📊</span>
                        <h3 class="text-sm font-bold text-slate-800">Informasi Belajar</h3>
                    </div>
                    
                    @php
                        $totalContents = collect($course->sections ?? [])->sum(fn($s) => count($s['section_contents'] ?? []));
                    @endphp
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold {{ $course->status === 'completed' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-indigo-500/10 text-indigo-600 border border-indigo-500/20' }}">
                                {{ $course->status === 'completed' ? 'Selesai' : 'Berjalan' }}
                            </span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Total Modul</span>
                            <span class="text-xs font-bold text-slate-850">{{ count($course->sections ?? []) }} Modul</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Total Materi</span>
                            <span class="text-xs font-bold text-slate-850">{{ $totalContents }} Materi</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Selesai</span>
                            <span class="text-xs font-bold text-slate-850">{{ $course->completed_count }} / {{ $totalContents }}</span>
                        </div>
                    </div>
                </div>

                {{-- Certificate Widget --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 sm:p-6">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                        <span class="text-base">🎓</span>
                        <h3 class="text-sm font-bold text-slate-800">Sertifikat Kelulusan</h3>
                    </div>
                    
                    @if (($course->status ?? '') === 'completed' || ($course->progress ?? 0) >= 100)
                        @if (!empty($course->certificate_file))
                            <div class="space-y-4">
                                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 text-center">
                                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-2 text-lg">✓</div>
                                    <p class="font-bold text-emerald-800 text-sm">Sertifikat Siap di Unduh</p>
                                </div>
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between border-b border-slate-50 pb-1.5">
                                        <span class="text-slate-400">Nomor</span>
                                        <span class="font-mono font-bold text-slate-800">{{ $course->certificate_code }}</span>
                                    </div>
                                    <div class="flex justify-between pb-1.5">
                                        <span class="text-slate-400">Tanggal Terbit</span>
                                        <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($course->certificate_issued_at)->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>
                                <a href="{{ $course->certificate_file }}" target="_blank" download
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl font-bold transition-colors flex items-center justify-center gap-2 text-sm shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Unduh Sertifikat
                                </a>
                            </div>
                        @else
                            <div class="space-y-4">
                                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 text-center">
                                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-2 text-lg">🎉</div>
                                    <p class="font-bold text-indigo-800 text-sm">Kursus Selesai!</p>
                                </div>
                                <form action="{{ route('user.course.my-course.generate-certificate', ['slug' => request()->route('slug')]) }}" method="POST"
                                    x-data="{ loading: false }"
                                    @submit="loading = true">
                                    @csrf
                                    <button type="submit"
                                        :disabled="loading"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl font-bold transition-colors flex items-center justify-center gap-2 text-sm disabled:opacity-55 disabled:cursor-not-allowed shadow-sm">
                                        <template x-if="!loading">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Generate Sertifikat
                                            </span>
                                        </template>
                                        <template x-if="loading">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Memproses...
                                            </span>
                                        </template>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="py-4 flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 bg-slate-50 border border-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-700">Sertifikat Belum Terbuka</p>
                            
                            @php
                                $remaining = $totalContents - ($course->completed_count ?? 0);
                            @endphp
                            
                            @if($remaining > 0)
                                <p class="text-[10px] text-slate-400 mt-2 max-w-[220px] leading-relaxed">
                                    Selesaikan <span class="font-extrabold text-indigo-600">{{ $remaining }} materi</span> lagi untuk menerbitkan sertifikat kelulusan Anda.
                                </p>
                            @else
                                <p class="text-[10px] text-slate-400 mt-2 max-w-[220px] leading-relaxed">
                                    Selesaikan seluruh modul materi hingga progress mencapai 100% untuk mengunduh sertifikat.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>
