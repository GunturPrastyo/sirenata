<x-dashboard::layouts.dashboard title="{{ data_get($content, 'name', 'Detail Materi') }} | SIRENATA">
    {{-- Lebar dibatasi ke max-w-5xl agar teks materi tidak terlalu memanjang (lebih nyaman dibaca) --}}
    <div class="p-4 md:p-6 max-w-full mx-auto min-h-screen">
        
        {{-- Main Content Container (Premium Card) --}}
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">

            {{-- Body Content --}}
            <div class="p-6 sm:p-10 lg:p-12">

                {{-- Tautan Kembali Kalem & Menyatu --}}
                <div class="mb-8">
                    <a href="{{ route('user.course.my-course.detail', $slug) }}"
                        class="inline-flex items-center gap-2 text-sm font-medium text-slate-400 hover:text-[#13416B] transition-colors">
                        <i class="fas fa-arrow-left text-xs"></i> 
                        <span>Kembali ke <span class="font-bold text-slate-600">{{ data_get($course, 'course_name', 'Kursus') }}</span></span>
                    </a>
                </div>

                {{-- Header & Title Materi --}}
                <div class="border-b border-slate-100 pb-8 mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-[#13416B] bg-[#13416B]/10 rounded-lg border border-[#13416B]/20">
                            Materi Pembelajaran
                        </span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
                        {{ data_get($content, 'name') }}
                    </h1>
                </div>

                {{-- 1. Text & Media Content (Mengalir natural) --}}
                @php
                    $contentText = is_object($content)
                        ? $content->content_text
                        : (is_array($content)
                            ? $content['content_text'] ?? null
                            : null);
                @endphp

                @if (!empty($contentText))
                    {{-- Menggunakan prose-lg pada layar besar agar ukuran font lebih profesional --}}
                    <div class="prose prose-slate sm:prose-lg max-w-none mb-12 text-slate-700 quill-content-render">
                        {!! $contentText !!}
                    </div>
                @endif

                {{-- 2. Document Attachment (Desain Premium) --}}
                @php
                    $docUrl = data_get($content, 'document_url') ?? data_get($content, 'document');
                @endphp

                @if ($docUrl)
                    <div class="{{ !empty($contentText) ? 'pt-8 border-t border-slate-100' : '' }}">
                        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-paperclip text-slate-400"></i> Lampiran Pendukung
                        </h3>
                        <a href="{{ $docUrl }}" target="_blank"
                            class="group flex items-center p-4 sm:p-5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-[#13416B]/5 hover:border-[#13416B]/30 transition-all duration-300">
                            <div class="w-14 h-14 rounded-xl bg-white border border-slate-200 flex items-center justify-center shrink-0 group-hover:border-[#13416B]/40 group-hover:shadow-md transition-all">
                                <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-base font-bold text-slate-800 group-hover:text-[#13416B] transition-colors leading-tight">
                                    Unduh Dokumen Materi
                                </p>
                                <p class="text-sm text-slate-500 mt-1">Format PDF/DOCX tersedia untuk dipelajari secara luring.</p>
                            </div>
                            <div class="hidden sm:flex w-10 h-10 rounded-full bg-white border border-slate-200 items-center justify-center text-slate-400 group-hover:text-white group-hover:bg-[#13416B] group-hover:border-[#13416B] transition-all">
                                <i class="fas fa-download text-sm"></i>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- Empty State (jika teks dan dokumen kosong) --}}
                @if (empty($contentText) && !$docUrl)
                    <div class="text-center py-16 px-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-white border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                            <i class="fas fa-tools text-3xl text-slate-300"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Materi Sedang Disiapkan</h3>
                        <p class="text-sm text-slate-500 mt-2 max-w-sm mx-auto">Tutor belum mengunggah konten bacaan, video, maupun dokumen lampiran untuk modul ini.</p>
                    </div>
                @endif
            </div>

            {{-- Action Footer --}}
            <div class="px-6 py-6 sm:px-10 border-t border-slate-100 bg-slate-50/80 flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('user.course.my-course.detail', $slug) }}"
                    class="w-full sm:w-auto text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors text-center py-2">
                    <i class="fas fa-list mr-2"></i> Daftar Modul
                </a>

                @if (!data_get($content, 'is_completed'))
                    <form action="{{ route('user.course.content.complete', ['content' => data_get($content, 'id')]) }}"
                        method="POST" class="w-full sm:w-auto m-0">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto px-8 py-3.5 text-sm font-bold text-white bg-[#13416B] rounded-xl hover:bg-[#0f3354] transition-all shadow-sm hover:shadow flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle text-lg"></i> Tandai Selesai & Lanjut
                        </button>
                    </form>
                @else
                    <span class="w-full sm:w-auto px-8 py-3.5 text-sm font-bold text-emerald-700 bg-emerald-100 border border-emerald-200 rounded-xl text-center flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-check-double text-lg"></i> Selesai Dipelajari
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
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 0.375rem; /* Sudut sangat tipis agar modern, tidak seperti card */
            /* box-shadow dihapus agar gambar terlihat kalem dan menyatu dengan teks */
        }

        /* 3. TANGKAP PENYELARASAN DARI INLINE STYLES (Modul BlotFormatter/Resize) */
        .quill-content-render img[style*="margin: auto"],
        .quill-content-render img[style*="display: block"],
        .quill-content-render iframe[style*="margin: auto"],
        .quill-content-render iframe[style*="display: block"] {
            display: block !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .quill-content-render img[style*="float: left"],
        .quill-content-render iframe[style*="float: left"] {
            float: left !important;
            margin-right: 1.5rem !important;
            margin-bottom: 1rem !important;
        }

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

        /* 5. Fix tampilan blok kode dari Highlight.js agar lebih estetik */
        .quill-content-render pre.ql-syntax {
            background-color: #0f172a !important; /* Warna slate-900 */
            color: #e2e8f0 !important;
            padding: 1.25rem !important;
            border-radius: 0.75rem !important;
            overflow-x: auto !important;
            text-align: left !important;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06) !important;
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
        }
    </style>
    @endpush
</x-dashboard::layouts.dashboard>