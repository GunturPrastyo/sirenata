<x-dashboard::layouts.dashboard title="Detail Materi: {{ $content->name }}">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-3 flex-wrap">
                <li>
                    <div class="flex items-center">
                        <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}"
                            class="ml-1 text-sm font-medium text-slate-700 hover:text-[#13416B] md:ml-2 transition-colors">
                            <i class="fas fa-home mr-2"></i> {{ $course->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span
                            class="ml-1 text-sm font-medium text-slate-500 md:ml-2">{{ $content->section->name }}</span>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">{{ $content->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="max-w-full mx-auto bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Header Materi -->
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $content->name }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Bagian: <span
                            class="font-medium text-slate-700">{{ $content->section->name }}</span></p>
                </div>
                <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}"
                    class="text-xs font-medium text-slate-600 hover:text-slate-900 bg-white border border-slate-300 px-3 py-1.5 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <div class="p-6 md:p-8 space-y-8">
                <!-- Bagian Video Player -->
                @php
                    $videoRaw = $content->video ?? $content->video_url;
                    $embedUrl = null;

                    if ($videoRaw) {
                        // Regex Super Akurat untuk semua format YouTube
                        if (
                            preg_match(
                                '/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|live\/|shorts\/))([\w-]{11})/',
                                $videoRaw,
                                $matches,
                            )
                        ) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?rel=0';
                        } else {
                            $embedUrl = $videoRaw;
                        }
                    }
                @endphp

                @if ($embedUrl)
                    <div class="w-full">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-play-circle text-[#13416B]"></i> Video Pembelajaran
                        </h3>
                        <div
                            class="aspect-video w-full rounded-xl overflow-hidden shadow-sm border border-slate-200 bg-black">
                            <iframe class="w-full h-full" src="{{ $embedUrl }}" frameborder="0"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                @endif

                <!-- Bagian Konten Teks (Rich Text) -->
                @if (!empty($content->content_text))
                    <div class="w-full">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-file-alt text-[#13416B]"></i> Materi Teks
                        </h3>
                        <!-- PERBAIKAN: Menambahkan class 'quill-content-render' -->
                        <div
                            class="prose prose-sm sm:prose max-w-none text-slate-700 bg-slate-50 p-6 rounded-xl border border-slate-100 quill-content-render">
                            {!! $content->content_text !!}
                        </div>
                    </div>
                @endif

                <!-- Bagian Dokumen -->
                @if ($content->document_url)
                    <div class="w-full">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-download text-[#13416B]"></i> Lampiran Dokumen
                        </h3>
                        <div
                            class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-white rounded-lg border border-slate-100 shadow-sm shrink-0">
                                    <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700">Dokumen Pendukung</p>
                                    <p class="text-xs text-slate-400">Klik tombol di samping untuk mengunduh atau
                                        melihat dokumen.</p>
                                </div>
                            </div>
                            <a href="{{ $content->document_url }}" target="_blank"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-[#13416B] text-white text-sm font-medium rounded-lg hover:bg-[#0f3354] transition-colors shrink-0">
                                Buka Dokumen
                            </a>
                        </div>
                    </div>
                @endif

                <!-- State Kosong -->
                @if (!$embedUrl && empty($content->content_text) && !$content->document_url)
                    <div class="text-center py-12">
                        <i class="fas fa-box-open text-4xl text-slate-300 mb-3"></i>
                        <p class="text-sm font-medium text-slate-600">Konten Kosong</p>
                        <p class="text-xs text-slate-400">Materi ini belum memiliki video, teks, maupun dokumen
                            lampiran.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
{{-- CSS Khusus untuk Mencegah Tailwind Prose Menimpa Gaya Quill --}}
    @push('styles')
    <style>
        /* 1. Paksa kelas perataan (alignment) bawaan Quill berfungsi */
        .quill-content-render .ql-align-center { text-align: center !important; }
        .quill-content-render .ql-align-right { text-align: right !important; }
        .quill-content-render .ql-align-justify { text-align: justify !important; }

        /* 2. Agar iframe dan image menuruti perataan parent-nya */
        .quill-content-render img,
        .quill-content-render iframe.ql-video {
            display: inline-block !important; 
            max-width: 100%;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        /* 3. TANGKAP PENYELARASAN DARI INLINE STYLES (Modul BlotFormatter/Resize) */
        /* Untuk Gambar & Video Rata Tengah */
        .quill-content-render img[style*="margin: auto"],
        .quill-content-render img[style*="display: block"],
        .quill-content-render iframe[style*="margin: auto"],
        .quill-content-render iframe[style*="display: block"] {
            display: block !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        /* Untuk Gambar & Video Rata Kiri */
        .quill-content-render img[style*="float: left"],
        .quill-content-render iframe[style*="float: left"] {
            float: left !important;
            margin-right: 1.5rem !important;
            margin-bottom: 1rem !important;
        }

        /* Untuk Gambar & Video Rata Kanan */
        .quill-content-render img[style*="float: right"],
        .quill-content-render iframe[style*="float: right"] {
            float: right !important;
            margin-left: 1.5rem !important;
            margin-bottom: 1rem !important;
        }

        /* 4. Fix Jarak Indentasi */
        .quill-content-render .ql-indent-1 { padding-left: 3em !important; }
        .quill-content-render .ql-indent-2 { padding-left: 6em !important; }
        .quill-content-render .ql-indent-3 { padding-left: 9em !important; }
        .quill-content-render .ql-indent-4 { padding-left: 12em !important; }

        /* 5. Fix tampilan blok kode dari Highlight.js */
        .quill-content-render pre.ql-syntax {
            background-color: #1e1e1e !important;
            color: #d4d4d4 !important;
            padding: 1.25rem !important;
            border-radius: 0.5rem !important;
            overflow-x: auto !important;
            text-align: left !important;
        }
    </style>
    @endpush
</x-dashboard::layouts.dashboard>
