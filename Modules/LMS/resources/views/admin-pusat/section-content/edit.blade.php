<x-dashboard::layouts.dashboard title="Edit Materi: {{ $content->name }}">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-3 flex-wrap">
                <li>
                    <div class="flex items-center">
                        <a href="{{ route('admin-pusat.management-course.courses.index') }}"
                            class="ml-1 text-sm font-medium text-slate-700 hover:text-indigo-600 md:ml-2">
                            <i class="fas fa-home mr-2"></i> Daftar Course
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}"
                            class="ml-1 text-sm font-medium text-slate-700 hover:text-indigo-600 md:ml-2">
                            {{ $course->name }}
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
            </ol>
        </nav>

        <!-- Form Card Container -->
        <div class="max-w-full mx-auto bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Edit Materi</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Bagian: <span
                            class="font-medium text-slate-700">{{ $content->section->name }}</span></p>
                </div>
                <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}"
                    class="text-xs font-medium text-slate-600 hover:text-slate-900 bg-white border border-slate-300 px-3 py-1.5 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <x-validation-errors class="p-6 pb-0" />

            <form action="{{ route('admin-pusat.management-course.course-sections-contents.update', $content->id) }}"
                method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <input type="hidden" name="course_slug" value="{{ $course->slug }}" />

                <!-- Nama / Judul Materi -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                        Nama Materi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required
                        value="{{ old('name', $content->name) }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                </div>

                <!-- Video URL -->
                <div>
                    <label for="video" class="block text-sm font-medium text-slate-700 mb-1">
                        Video URL <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="url" id="video" name="video" value="{{ old('video', $content->video) }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                </div>

                <!-- Dokumen -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Dokumen Materi <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>

                    @if ($content->document_url)
                        <div class="mb-2 text-sm flex items-center gap-2">
                            <span class="text-slate-500">Dokumen saat ini:</span>
                            <a href="{{ $content->document_url }}" target="_blank"
                                class="text-indigo-600 hover:underline flex items-center gap-1 font-medium">
                                <i class="fas fa-file-alt"></i> Lihat Dokumen
                            </a>
                        </div>
                    @endif

                    <input type="file" name="document" accept=".pdf,.doc,.docx"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    <p class="text-[10px] text-slate-400 mt-1">Format: PDF, DOC, DOCX (Max: 10MB). Unggah file baru jika
                        ingin mengganti dokumen lama.</p>
                </div>

                <!-- Rich Text Editor (Quill.js) -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Isi Materi (Teks, Gambar, Kode, Video) <span
                            class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="prose max-w-none bg-white">
                        <!-- Hidden input untuk menampung data update -->
                        <input type="hidden" name="content_text" id="content_text"
                            value="{{ old('content_text', $content->content_text) }}">
                        <!-- Container untuk Quill -->
                        <div id="editor-container">{!! old('content_text', $content->content_text) !!}</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end items-center gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}"
                        class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                        Update Materi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quill, Highlight.js, KaTeX, dan BlotFormatter Scripts -->
    @push('scripts')
        <!-- Highlight.js (Untuk sintaks blok kode) -->
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

        <!-- KaTeX (Untuk rumus matematika) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>

        <!-- Quill Core -->
        <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

        <!-- MODUL BARU: Quill Blot Formatter (Untuk Resize & Posisi Gambar/Video) -->
        <script src="https://cdn.jsdelivr.net/npm/quill-blot-formatter@1.0.5/dist/quill-blot-formatter.min.js"></script>

        <script>
            // 1. Mendaftarkan modul tambahan ke Quill
            Quill.register('modules/blotFormatter', QuillBlotFormatter.default);

            // =========================================================================
            // 2. KUNCI UTAMA: MEMBUAT CUSTOM BLOT AGAR QUILL TIDAK MENGHAPUS STYLE/UKURAN
            // =========================================================================
            const BaseImage = Quill.import('formats/image');
            class CustomImage extends BaseImage {
                static formats(domNode) {
                    return ['style', 'width', 'height'].reduce(function(formats, attribute) {
                        if (domNode.hasAttribute(attribute)) {
                            formats[attribute] = domNode.getAttribute(attribute);
                        }
                        return formats;
                    }, {});
                }
                format(name, value) {
                    if (['style', 'width', 'height'].includes(name)) {
                        if (value) { this.domNode.setAttribute(name, value); } 
                        else { this.domNode.removeAttribute(name); }
                    } else { super.format(name, value); }
                }
            }
            Quill.register(CustomImage, true);

            const BaseVideo = Quill.import('formats/video');
            class CustomVideo extends BaseVideo {
                static formats(domNode) {
                    return ['style', 'width', 'height'].reduce(function(formats, attribute) {
                        if (domNode.hasAttribute(attribute)) {
                            formats[attribute] = domNode.getAttribute(attribute);
                        }
                        return formats;
                    }, {});
                }
                format(name, value) {
                    if (['style', 'width', 'height'].includes(name)) {
                        if (value) { this.domNode.setAttribute(name, value); } 
                        else { this.domNode.removeAttribute(name); }
                    } else { super.format(name, value); }
                }
            }
            Quill.register(CustomVideo, true);
            // =========================================================================

            // Setup bahasa pemrograman yang didukung highlight.js
            hljs.configure({
                languages: ['javascript', 'php', 'html', 'css', 'python', 'java', 'sql', 'bash']
            });

            // Inisialisasi Quill
            var quill = new Quill('#editor-container', {
                modules: {
                    syntax: true,
                    blotFormatter: {}, // Modul resize dan posisi gambar/video
                    toolbar: [
                        [{ 'size': ['small', false, 'large', 'huge'] }],
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        [{ 'indent': '-1' }, { 'indent': '+1' }],
                        [{ 'align': [] }],
                        ['blockquote', 'code-block', 'formula'],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                },
                placeholder: 'Tuliskan materi pembelajaran di sini...',
                theme: 'snow'
            });

            // LOGIKA UTAMA SINKRONISASI DATA
            quill.on('text-change', function() {
                var contentText = document.getElementById('content_text');
                if (quill.getText().trim().length === 0 && !quill.root.innerHTML.includes('<img') && !quill.root.innerHTML.includes('<iframe')) {
                    contentText.value = '';
                } else {
                    contentText.value = quill.root.innerHTML;
                }
            });
        </script>

       <style>
            /* Customizing Quill UI to fit Tailwind better */
            .ql-toolbar.ql-snow {
                border-top-left-radius: 0.5rem;
                border-top-right-radius: 0.5rem;
                border-color: #cbd5e1;
                background-color: #f8fafc;
            }

            .ql-container.ql-snow {
                border-bottom-left-radius: 0.5rem;
                border-bottom-right-radius: 0.5rem;
                border-color: #cbd5e1;
                min-height: 400px;
                font-family: inherit;
                font-size: 0.875rem;
            }

            .ql-editor {
                min-height: 400px;
            }

            /* Fix Tailwind Prose conflict with Quill Code Block */
            .ql-editor pre.ql-syntax {
                background-color: #1e1e1e;
                color: #d4d4d4;
                padding: 1rem;
                border-radius: 0.5rem;
            }

            /* ======================================================
               PERBAIKAN TOOLTIP (MODAL INPUT LINK & VIDEO)
               ====================================================== */
            
            /* Mengubah kotak input menjadi Modal di tengah layar */
            .ql-snow .ql-tooltip {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                z-index: 9999 !important;
                background-color: #ffffff !important;
                border: 1px solid #e2e8f0 !important;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
                border-radius: 0.75rem !important;
                padding: 1.5rem !important;
                width: 90% !important;
                max-width: 450px !important;
                white-space: normal !important;
            }

            /* Sembunyikan segitiga panah bawaan tooltip */
            .ql-snow .ql-tooltip::after {
                display: none !important; 
            }

            /* Ubah teks label menjadi lebih jelas */
            .ql-snow .ql-tooltip::before {
                display: block !important;
                content: "Masukkan Tautan (URL) Video atau Link:" !important;
                font-size: 1rem !important;
                font-weight: 700 !important;
                color: #1e293b !important;
                margin-bottom: 0.5rem !important;
            }

            /* Percantik kolom input URL */
            .ql-snow .ql-tooltip input[type="text"] {
                width: 100% !important;
                margin: 0.5rem 0 1.25rem 0 !important;
                border: 1px solid #cbd5e1 !important;
                border-radius: 0.5rem !important;
                padding: 0.6rem 1rem !important;
                font-size: 0.875rem !important;
                color: #334155 !important;
                outline: none !important;
                box-sizing: border-box !important;
                transition: all 0.2s ease-in-out;
            }
            .ql-snow .ql-tooltip input[type="text"]:focus {
                border-color: #13416B !important;
                box-shadow: 0 0 0 1px #13416B !important;
            }

            /* Percantik tombol Action (Simpan/Save) */
            .ql-snow .ql-tooltip a.ql-action {
                color: #fff !important;
                background-color: #13416B !important;
                padding: 0.5rem 1.25rem !important;
                border-radius: 0.5rem !important;
                font-weight: 600 !important;
                text-decoration: none !important;
                display: inline-block !important;
                float: right !important;
                transition: background-color 0.2s;
            }
            .ql-snow .ql-tooltip a.ql-action:hover {
                background-color: #0f3354 !important;
            }

            /* Percantik tombol Remove (Batal/Clear) */
            .ql-snow .ql-tooltip a.ql-remove {
                color: #ef4444 !important;
                background-color: #fef2f2 !important;
                border: 1px solid #fecaca !important;
                padding: 0.5rem 1.25rem !important;
                border-radius: 0.5rem !important;
                font-weight: 600 !important;
                text-decoration: none !important;
                display: inline-block !important;
                float: right !important;
                margin-right: 0.5rem !important;
                transition: all 0.2s;
            }
            .ql-snow .ql-tooltip a.ql-remove:hover {
                background-color: #fee2e2 !important;
            }
        </style>
    @endpush
</x-dashboard::layouts.dashboard>
