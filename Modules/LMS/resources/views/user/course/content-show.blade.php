<x-dashboard::layouts.dashboard title="{{ data_get($content, 'name', 'Detail Materi') }} | SIRENATA">
    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto min-h-screen">
        {{-- Breadcrumb / Back Button --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('user.course.my-course.detail', $slug) }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali ke {{ data_get($course, 'course_name', 'Kursus') }}
            </a>
        </div>

        {{-- Main Content Container --}}
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">

            {{-- 1. Video Section (Menyatu di bagian atas) --}}
            @php
                $videoRaw = data_get($content, 'video') ?? data_get($content, 'video_url');
                $embedUrl = null;

                if ($videoRaw) {
                    // Regex Super Akurat untuk semua format YouTube (watch, youtu.be, embed, live, shorts)
                    if (
                        preg_match(
                            '/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|live\/|shorts\/))([\w-]{11})/',
                            $videoRaw,
                            $matches,
                        )
                    ) {
                        // $matches[1] akan mengambil 11 karakter ID video YouTube
                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?rel=0';
                    } else {
                        // Fallback jika ternyata link web biasa (bukan youtube)
                        $embedUrl = $videoRaw;
                    }
                }
            @endphp

            @if ($embedUrl)
                <div class="w-full bg-slate-900 aspect-video relative border-b border-slate-200">
                    <iframe class="absolute top-0 left-0 w-full h-full" src="{{ $embedUrl }}" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            @endif

            {{-- Body Content --}}
            <div class="p-6 sm:p-8 lg:p-10">

                {{-- Title --}}
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-6 leading-tight">
                    {{ data_get($content, 'name') }}
                </h1>

                {{-- 2. Text Content (Mengalir tanpa border tambahan) --}}
                @php
                    $contentText = is_object($content)
                        ? $content->content_text
                        : (is_array($content)
                            ? $content['content_text'] ?? null
                            : null);
                @endphp

                @if (!empty($contentText))
                    {{-- PERBAIKAN: Menambahkan div pembungkus ql-editor khusus untuk rendering yang dihasilkan Quill --}}
                    <div
                        class="prose prose-sm sm:prose prose-slate max-w-none mb-8 leading-relaxed text-slate-700 quill-content-render">
                        {!! $contentText !!}
                    </div>
                @endif

                {{-- 3. Document Attachment (Menempel elegan di bawah teks) --}}
                @php
                    $docUrl = data_get($content, 'document_url') ?? data_get($content, 'document');
                @endphp

                @if ($docUrl)
                    <div class="{{ !empty($contentText) ? 'mt-8 pt-8 border-t border-slate-100' : '' }}">
                        <a href="{{ $docUrl }}" target="_blank"
                            class="group flex items-center p-4 rounded-lg border border-slate-200 bg-slate-50 hover:bg-[#13416B]/5 hover:border-[#13416B]/20 transition-all duration-200">
                            <div
                                class="w-12 h-12 rounded-lg bg-white border border-slate-200 flex items-center justify-center shrink-0 group-hover:border-[#13416B]/30 transition-colors shadow-sm">
                                <i class="fas fa-file-pdf text-xl text-red-500"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p
                                    class="text-sm font-bold text-slate-800 group-hover:text-[#13416B] transition-colors">
                                    Materi Pendukung (Dokumen/PDF)</p>
                                <p class="text-xs text-slate-500 mt-0.5">Klik untuk mengunduh atau membaca lampiran
                                    materi ini.</p>
                            </div>
                            <div class="pl-4 shrink-0 text-slate-400 group-hover:text-[#13416B] transition-colors">
                                <i class="fas fa-download text-lg"></i>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- Empty State (jika ketiganya kosong) --}}
                @if (!$embedUrl && empty($contentText) && !$docUrl)
                    <div class="text-center py-12">
                        <i class="fas fa-folder-open text-5xl text-slate-200 mb-4"></i>
                        <p class="text-base font-bold text-slate-700">Materi Sedang Disiapkan</p>
                        <p class="text-sm text-slate-500 mt-1">Belum ada konten media atau tulisan yang diunggah untuk
                            modul ini.</p>
                    </div>
                @endif
            </div>

            {{-- Action Footer --}}
            <div
                class="px-6 py-5 sm:px-8 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('user.course.my-course.detail', $slug) }}"
                    class="w-full sm:w-auto text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors text-center">
                    <i class="fas fa-list-ul mr-1.5"></i> Lihat Modul Lainnya
                </a>

                @if (!data_get($content, 'is_completed'))
                    <form action="{{ route('user.course.content.complete', ['content' => data_get($content, 'id')]) }}"
                        method="POST" class="w-full sm:w-auto m-0">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-white bg-[#13416B] rounded-lg hover:bg-[#0f3354] transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i> Selesai & Lanjutkan
                        </button>
                    </form>
                @else
                    <span
                        class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-emerald-700 bg-emerald-100 border border-emerald-200 rounded-lg text-center flex items-center justify-center gap-2">
                        <i class="fas fa-check-double"></i> Selesai Dipelajari
                    </span>
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
