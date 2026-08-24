<x-dashboard::layouts.dashboard title="Tambah Materi: {{ $section->name }}">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-3 flex-wrap">
               
                <li>
                    <div class="flex items-center">
                       
                        <a href="{{ route('admin-pusat.management-course.courses.index') }}" class="ml-1 text-sm font-medium text-slate-700 hover:text-indigo-600 md:ml-2">
                           <i class="fas fa-home mr-2"></i>  Daftar Course
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}" class="ml-1 text-sm font-medium text-slate-700 hover:text-indigo-600 md:ml-2">
                            {{ $course->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">{{ $section->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Form Card Container -->
        <div class="max-w-full mx-auto bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Tambah Materi Baru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Bagian: <span class="font-medium text-slate-700">{{ $section->name }}</span></p>
                </div>
                <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}" class="text-xs font-medium text-slate-600 hover:text-slate-900 bg-white border border-slate-300 px-3 py-1.5 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <x-validation-errors class="p-6 pb-0" />

            <form action="{{ route('admin-pusat.management-course.course-sections-contents.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="course_slug" value="{{ $course->slug }}" />
                <input type="hidden" name="course_section_id" value="{{ $section->id }}" />

                <!-- Nama / Judul Materi -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                        Nama Materi <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name"
                        name="name" 
                        required 
                        value="{{ old('name') }}" 
                        placeholder="Contoh: Bab 1 - Pengenalan Dasar" 
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    />
                </div>

                <!-- Video URL -->
                <div>
                    <label for="video" class="block text-sm font-medium text-slate-700 mb-1">
                        Video URL <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input 
                        type="url" 
                        id="video"
                        name="video" 
                        value="{{ old('video') }}" 
                        placeholder="https://www.youtube.com/watch?v=..." 
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    />
                </div>

                <!-- Dokumen -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Dokumen Materi <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="file" name="document" accept=".pdf,.doc,.docx" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    <p class="text-[10px] text-slate-400 mt-1">Format: PDF, DOC, DOCX (Max: 10MB)</p>
                </div>

                <!-- Rich Text Editor (Materi & Gambar) -->
                <div>
                    <label for="editor" class="block text-sm font-medium text-slate-700 mb-1">
                        Isi Materi (Teks & Gambar) <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="prose max-w-none">
                        <textarea 
                            id="editor" 
                            name="content_text" 
                            placeholder="Tuliskan materi pembelajaran di sini..."
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        >{{ old('content_text') }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end items-center gap-3 pt-6 border-t border-slate-100">
                    <a 
                        href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}" 
                        class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors"
                    >
                        Batal
                    </a>
                    <button 
                        type="submit" 
                        class="px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm"
                    >
                        Simpan Materi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script CKEditor 5 -->
    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    toolbar: [
                        'heading', '|', 
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                        'insertTable', 'imageUpload', '|', 
                        'undo', 'redo'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
        <style>
            .ck-editor__editable_inline {
                min-height: 350px;
            }
        </style>
    @endpush
</x-dashboard::layouts.dashboard>