<x-dashboard::layouts.dashboard title="My Course | SIRENATA">
    <div class="p-2 sm:p-6">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li>
                    <a
                        href="{{ url('/') }}"
                        class="text-sm font-medium text-gray-700 hover:text-indigo-600"
                    >
                        Home
                    </a>
                </li>
                <li>
                    <span class="ml-1 text-sm font-medium text-gray-500">
                        / {{ $course->course_name }}
                    </span>
                </li>
            </ol>
        </nav>

        {{-- Header Status --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-6 text-white mb-6">
            <h1 class="text-2xl font-bold">{{ $course->course_name }}</h1>
            <p>Progress: {{ $course->progress }}%</p>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">📚 Daftar Modul</h2>

                <div
                    x-data="{ activeAccordion: '' }"
                    class="w-full text-sm bg-white border border-gray-200 divide-y divide-gray-200 rounded-md"
                >
                    {{-- Loop Sections --}}
                    @forelse ($course->sections ?? [] as $index => $section)
                        @php
                            // Pastikan section diubah ke objek
                            $section = (object) $section;
                        @endphp

                        <div x-data="{ id: 'section-{{ $index }}' }" class="group">
                            <button
                                @click="activeAccordion = (activeAccordion == id ? '' : id)"
                                class="flex items-center justify-between w-full p-4 text-left hover:bg-gray-50"
                            >
                                <h3 class="font-semibold text-gray-900">
                                    Modul {{ $index + 1 }}: {{ $section->name }}
                                </h3>
                                <svg
                                    class="w-4 h-4 transition-transform"
                                    :class="{ 'rotate-180': activeAccordion == id }"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    fill="none"
                                >
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                            </button>

                            <div x-show="activeAccordion == id" x-collapse x-cloak>
                                <div class="p-4 pt-0">
                                    {{-- Loop Contents --}}
                                    @forelse ($section->section_contents as $content)
                                        @php
                                            $content = (object) $content;
                                        @endphp

                                        <div
                                            class="flex items-center justify-between border border-gray-300 rounded p-3 mt-2"
                                        >
                                            <div>
                                                <p class="font-medium text-gray-800">
                                                    {{ $content->name }}
                                                </p>

                                                {{-- Bagian Status yang ditambahkan --}}
                                                <div class="mt-1">
                                                    @if ($content->is_completed)
                                                        <span
                                                            class="inline-flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full"
                                                        >
                                                            <svg
                                                                class="w-3 h-3 mr-1"
                                                                fill="currentColor"
                                                                viewBox="0 0 20 20"
                                                            >
                                                                <path
                                                                    fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd"
                                                                ></path>
                                                            </svg>
                                                            Selesai
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full"
                                                        >
                                                            Belum Selesai
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Logic untuk memproses URL Video --}}
                                            @php
                                                $embedUrl = null;
                                                $url = $content->video_url ?? null; // Sesuai dd terbaru: field-nya adalah 'video'

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

                                            <button
                                                type="button"
                                                @click="$dispatch('open-modal', 'show-content-{{ $content->id }}')"
                                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm"
                                            >
                                                Lihat Modul
                                            </button>

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
                                                                                Klik untuk membuka
                                                                                dokumen
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
                                                                    Materi ini belum memiliki video
                                                                    atau dokumen pendukung.
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
                                        <p class="text-gray-500">Belum ada materi.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="p-4">Belum ada modul.</p>
                    @endforelse
                </div>
            </div>

            {{-- Certificate Section --}}
            @if (($course->status ?? '') === 'completed' || ($course->progress ?? 0) >= 100)
                <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        🎓 Sertifikat Kelulusan
                    </h2>

                    @if (!empty($course->certificate_file))
                        {{-- Already generated --}}
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-emerald-100 rounded-full">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-emerald-800">Sertifikat Sudah Diterbitkan</p>
                                    <p class="text-sm text-emerald-600">Anda dapat mengunduh sertifikat Anda di bawah ini.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                                <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                    <p class="text-xs text-gray-500 mb-1">Nomor Sertifikat</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $course->certificate_code }}</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                    <p class="text-xs text-gray-500 mb-1">Tanggal Terbit</p>
                                    <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($course->certificate_issued_at)->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>

                            <a href="{{ $course->certificate_file }}" target="_blank"
                                class="w-full bg-emerald-600 text-white py-3 rounded-xl font-semibold hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2 text-sm sm:text-base">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh Sertifikat
                            </a>
                        </div>
                    @else
                        {{-- Not yet generated --}}
                        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 bg-indigo-100 rounded-full">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-indigo-800">Selamat! Anda Telah Menyelesaikan Kursus Ini 🎉</p>
                                    <p class="text-sm text-indigo-600">Klik tombol di bawah untuk membuat sertifikat kelulusan Anda.</p>
                                </div>
                            </div>

                            <form action="{{ route('user.course.my-course.generate-certificate', ['slug' => request()->route('slug')]) }}" method="POST"
                                x-data="{ loading: false }"
                                @submit="loading = true">
                                @csrf
                                <button type="submit"
                                    :disabled="loading"
                                    :class="loading ? 'opacity-60 cursor-not-allowed' : 'hover:bg-indigo-700'"
                                    class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold transition-colors flex items-center justify-center gap-2 text-sm sm:text-base">
                                    <template x-if="!loading">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Generate Sertifikat
                                        </span>
                                    </template>
                                    <template x-if="loading">
                                        <span class="flex items-center gap-2">
                                            <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Sedang memproses...
                                        </span>
                                    </template>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-dashboard::layouts.dashboard>
