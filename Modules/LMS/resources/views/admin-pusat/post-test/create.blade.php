<x-dashboard::layouts.dashboard title="Buat Post Test & Soal">
    <div class="p-2 sm:p-6" x-data="postTestForm()">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-3 flex-wrap">
                <li>
                    <div class="flex items-center">
                        <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}" class="ml-1 text-sm font-medium text-slate-700 hover:text-[#13416B] md:ml-2">
                           <i class="fas fa-home mr-2"></i>  {{ $course->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">
                            {{ $section ? $section->name : 'Evaluasi Akhir Course' }}
                        </span>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">Buat Soal Post Test</span>
                    </div>
                </li>
            </ol>
        </nav>

        <form action="{{ route('admin-pusat.management-course.post-tests.store') }}" method="POST" class="max-w-full mx-auto space-y-6">
            @csrf
            <!-- Hidden Inputs -->
            <input type="hidden" name="course_slug" value="{{ $course->slug }}" />
            <input type="hidden" name="course_id" value="{{ $course->id }}" />
            @if($section)
                <input type="hidden" name="course_section_id" value="{{ $section->id }}" />
            @endif

            <!-- CARD 1: INFORMASI UTAMA POST TEST -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-base font-bold text-slate-800">1. Pengaturan Umum Post Test</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Judul Post Test <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required value="{{ old('title') }}" placeholder="Contoh: Ujian Evaluasi Pemahaman Bab 1" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Passing Score (KKM) <span class="text-red-500">*</span></label>
                            <input type="number" name="passing_score" required min="0" max="100" value="{{ old('passing_score', 70) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Durasi (Menit) <span class="text-red-500">*</span></label>
                            <input type="number" name="duration" required min="1" value="{{ old('duration', 30) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Instruksi Pengerjaan (Opsional)</label>
                        <textarea name="description" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- CARD 2: DAFTAR SOAL PILIHAN GANDA DENGAN FULL TEXT EDITOR -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-800">2. Daftar Pertanyaan Pilihan Ganda</h2>
                </div>

                <template x-for="(q, qIndex) in questions" :key="qIndex">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative group">
                        <div class="px-6 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider" x-text="'Pertanyaan Nomor ' + (qIndex + 1)"></span>
                            <button type="button" @click="removeQuestion(qIndex)" x-show="questions.length > 1" class="text-red-500 hover:text-red-700 text-xs font-medium flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> Hapus Soal
                            </button>
                        </div>

                        <div class="p-6 space-y-5">
                            <!-- TEXT EDITOR: Soal -->
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Tulis Pertanyaan <span class="text-red-500">*</span></label>
                                <div x-data="{
                                        initEditor() {
                                            let quill = new Quill(this.$refs.editor_q, {
                                                theme: 'snow',
                                                placeholder: 'Tuliskan isi pertanyaan di sini...',
                                                modules: { 
                                                    syntax: true,
                                                    blotFormatter: {},
                                                    toolbar: {
                                                        container: [
                                                            [{ 'size': ['small', false, 'large', 'huge'] }],
                                                            [{ 'header': [1, 2, 3, false] }],
                                                            ['bold', 'italic', 'underline', 'strike'],
                                                            [{ 'color': [] }, { 'background': [] }],
                                                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                                                            [{ 'align': [] }],
                                                            ['blockquote', 'code-block', 'formula'],
                                                            ['link', 'image', 'video'],
                                                            ['clean']
                                                        ],
                                                        handlers: { 
                                                            image: function() { selectLocalImage(this.quill); },
                                                            link: function(value) { customLinkHandler(this.quill, value); }
                                                        }
                                                    }
                                                }
                                            });
                                            quill.on('text-change', () => { 
                                                q.question = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML; 
                                            });
                                        }
                                    }" x-init="initEditor()">
                                    <div x-ref="editor_q" class="bg-white quill-question"></div>
                                    <input type="hidden" :name="'questions['+qIndex+'][question]'" :value="q.question">
                                </div>
                            </div>

                            <!-- TEXT EDITOR: Pilihan Ganda -->
                            <div class="space-y-3">
                                <label class="block text-xs font-medium text-slate-500 mb-1.5">Pilihan Jawaban (Tandai lingkaran untuk jawaban yang benar) <span class="text-red-500">*</span></label>
                                
                                <template x-for="(choice, cIndex) in q.choices" :key="cIndex">
                                    <div class="flex items-start gap-3 bg-slate-50/50 p-3 rounded-xl border border-slate-200">
                                        <input type="radio" :name="'questions['+qIndex+'][correct_choice]'" :value="cIndex" x-model="q.correct_choice" required class="w-4 h-4 text-[#13416B] focus:ring-[#13416B] border-slate-300 mt-3.5 shrink-0" title="Tandai sebagai jawaban benar">
                                        
                                        <div class="w-full flex-1">
                                            <div x-data="{
                                                    initChoiceEditor() {
                                                        let quill = new Quill(this.$refs.editor_c, {
                                                            theme: 'snow',
                                                            placeholder: 'Pilihan ' + String.fromCharCode(65 + cIndex),
                                                            modules: { 
                                                                syntax: true,
                                                                blotFormatter: {},
                                                                toolbar: {
                                                                    container: [ 
                                                                        ['bold', 'italic', 'underline'], 
                                                                        [{ 'script': 'sub'}, { 'script': 'super' }], 
                                                                        ['formula', 'code-block', 'image'] 
                                                                    ],
                                                                    handlers: { image: function() { selectLocalImage(this.quill); } }
                                                                }
                                                            }
                                                        });
                                                        quill.on('text-change', () => { 
                                                            q.choices[cIndex] = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML; 
                                                        });
                                                    }
                                                }" x-init="initChoiceEditor()">
                                                <div x-ref="editor_c" class="bg-white quill-choice"></div>
                                                <input type="hidden" :name="'questions['+qIndex+'][choices]['+cIndex+']'" :value="q.choices[cIndex]">
                                            </div>
                                        </div>
                                        
                                        <button type="button" @click="q.choices.splice(cIndex, 1)" x-show="q.choices.length > 2" class="text-slate-400 hover:text-red-500 p-2 mt-2 shrink-0">
                                            <i class="fas fa-times text-lg"></i>
                                        </button>
                                    </div>
                                </template>

                                <button type="button" @click="q.choices.push('')" class="mt-3 text-xs font-medium text-[#13416B] hover:text-[#0f3354] flex items-center gap-1.5 px-2">
                                    <i class="fas fa-plus-circle"></i> Tambah Pilihan Opsi
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="pt-2">
                    <button type="button" @click="addQuestion()" class="w-full py-3 border-2 border-dashed border-[#13416B]/30 text-[#13416B] bg-[#13416B]/5 hover:bg-[#13416B]/10 text-sm font-bold rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-plus-circle text-lg"></i> Tambah Pertanyaan Baru
                    </button>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 pb-12">
                <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}" class="px-5 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition shadow-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Post Test & Soal
                </button>
            </div>
        </form>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL KUSTOM UNTUK INSERT LINK (GAYA CANVA) -->
    <!-- ========================================================================= -->
    <div id="custom-link-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 border border-slate-200 transform scale-100 transition-transform">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-link text-[#13416B]"></i> Tambahkan Tautan
                </h3>
                <button type="button" id="btn-close-link" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Teks yang ditampilkan</label>
                    <input type="text" id="link-text-input" placeholder="Contoh: Klik disini untuk mengunduh" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#13416B] focus:ring-1 focus:ring-[#13416B] transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">URL / Tautan <span class="text-red-500">*</span></label>
                    <input type="url" id="link-url-input" placeholder="https://..." class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-[#13416B] focus:ring-1 focus:ring-[#13416B] transition-all">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button type="button" id="btn-cancel-link" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Batal</button>
                <button type="button" id="btn-save-link" class="px-5 py-2.5 text-sm font-bold text-white bg-[#13416B] hover:bg-[#0f3354] shadow-sm rounded-xl transition-colors flex items-center gap-2">
                    <i class="fas fa-check"></i> Simpan
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Highlight.js (Untuk sintaks blok kode) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css" />
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
        // Mendaftarkan modul resize & perbaikan bug quill format gambar
        Quill.register('modules/blotFormatter', QuillBlotFormatter.default);

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
                    if (value) { this.domNode.setAttribute(name, value); } else { this.domNode.removeAttribute(name); }
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
                    if (value) { this.domNode.setAttribute(name, value); } else { this.domNode.removeAttribute(name); }
                } else { super.format(name, value); }
            }
        }
        Quill.register(CustomVideo, true);

        // Setup Highlight.js
        hljs.configure({ languages: ['javascript', 'php', 'html', 'css', 'python', 'java', 'sql', 'bash'] });

        // FUNGSI UPLOAD GAMBAR KE SERVER LARAVEL
        function selectLocalImage(quillInstance) {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/png, image/gif, image/jpeg, image/webp');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                if (file) {
                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('_token', '{{ csrf_token() }}'); // Ambil token CSRF Blade

                    try {
                        const range = quillInstance.getSelection(true);
                        quillInstance.insertText(range.index, 'Mengunggah gambar...', 'user');

                        const response = await fetch("{{ route('admin-pusat.management-course.post-tests.upload-image') }}", {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();
                        quillInstance.deleteText(range.index, 20); // Hapus teks loading

                        if (result.success && result.url) {
                            quillInstance.insertEmbed(range.index, 'image', result.url);
                        } else {
                            alert('Gagal mengunggah gambar. Pastikan format dan ukuran sesuai.');
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan pada server saat mengunggah gambar.');
                        console.error(error);
                    }
                }
            };
        }

        // FUNGSI UNTUK MENANGANI TOMBOL LINK CUSTOM
        window.activeQuillForLink = null;
        function customLinkHandler(quillInstance, value) {
            window.activeQuillForLink = quillInstance;
            var range = quillInstance.getSelection(true);
            var text = '';

            if (range && range.length > 0) {
                text = quillInstance.getText(range.index, range.length);
            }

            document.getElementById('link-text-input').value = text;
            document.getElementById('link-url-input').value = '';

            const modal = document.getElementById('custom-link-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => document.getElementById('link-url-input').focus(), 100);
            window.quillLinkRange = range;
        }

        function closeLinkModal() {
            const modal = document.getElementById('custom-link-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            window.activeQuillForLink = null;
        }

        document.getElementById('btn-close-link').addEventListener('click', closeLinkModal);
        document.getElementById('btn-cancel-link').addEventListener('click', closeLinkModal);

        document.getElementById('btn-save-link').addEventListener('click', function() {
            if (!window.activeQuillForLink) return;

            var text = document.getElementById('link-text-input').value.trim();
            var url = document.getElementById('link-url-input').value.trim();
            var range = window.quillLinkRange;
            var quill = window.activeQuillForLink;

            if (!url) {
                alert('URL / Tautan wajib diisi!');
                return;
            }

            if (!/^https?:\/\//i.test(url) && !/^mailto:/i.test(url) && !/^tel:/i.test(url)) {
                url = 'https://' + url;
            }
            if (!text) { text = url; }

            quill.focus();

            if (range && range.length > 0) {
                quill.deleteText(range.index, range.length);
                quill.insertText(range.index, text, 'link', url);
                quill.setSelection(range.index + text.length);
            } else {
                var cursorPosition = range ? range.index : quill.getLength();
                quill.insertText(cursorPosition, text, 'link', url);
                quill.setSelection(cursorPosition + text.length);
            }
            closeLinkModal();
        });

        function postTestForm() {
            return {
                questions: [
                    { question: '', choices: ['', '', '', ''], correct_choice: 0 }
                ],
                addQuestion() {
                    this.questions.push({ question: '', choices: ['', '', '', ''], correct_choice: 0 });
                },
                removeQuestion(index) {
                    this.questions.splice(index, 1);
                }
            }
        }
    </script>
    <style>
        .quill-question .ql-editor { min-height: 120px; font-family: inherit; font-size: 14px; }
        .quill-choice .ql-editor { min-height: 60px; font-family: inherit; font-size: 14px; }
        .ql-toolbar { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; border-color: #cbd5e1 !important; background-color: #f8fafc; }
        .ql-container { border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; border-color: #cbd5e1 !important; }
        .ql-editor pre.ql-syntax { background-color: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 0.5rem; }
        
        /* Modal Tooltip Custom (seperti di form materi) */
        .ql-snow .ql-tooltip {
            position: fixed !important; top: 50% !important; left: 50% !important;
            transform: translate(-50%, -50%) !important; z-index: 9999 !important;
            background-color: #ffffff !important; border: 1px solid #e2e8f0 !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important; border-radius: 0.75rem !important;
            padding: 1.5rem !important; width: 90% !important; max-width: 450px !important; white-space: normal !important;
        }
        .ql-snow .ql-tooltip::after { display: none !important; }
        .ql-snow .ql-tooltip::before {
            display: block !important; content: "Masukkan Tautan (URL) Video atau Link:" !important;
            font-size: 1rem !important; font-weight: 700 !important; color: #1e293b !important; margin-bottom: 0.5rem !important;
        }
        .ql-snow .ql-tooltip input[type="text"] {
            width: 100% !important; margin: 0.5rem 0 1.25rem 0 !important; border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important; padding: 0.6rem 1rem !important; font-size: 0.875rem !important; outline: none !important;
        }
        .ql-snow .ql-tooltip input[type="text"]:focus { border-color: #13416B !important; box-shadow: 0 0 0 1px #13416B !important; }
        .ql-snow .ql-tooltip a.ql-action {
            color: #fff !important; background-color: #13416B !important; padding: 0.5rem 1.25rem !important; border-radius: 0.5rem !important;
            font-weight: 600 !important; text-decoration: none !important; display: inline-block !important; float: right !important;
        }
        .ql-snow .ql-tooltip a.ql-remove {
            color: #ef4444 !important; background-color: #fef2f2 !important; border: 1px solid #fecaca !important; padding: 0.5rem 1.25rem !important;
            border-radius: 0.5rem !important; font-weight: 600 !important; text-decoration: none !important; display: inline-block !important; float: right !important; margin-right: 0.5rem !important;
        }
    </style>
    @endpush
</x-dashboard::layouts.dashboard>