<x-dashboard::layouts.dashboard title="Edit Rencana Tenaga Kerja Nasional">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-pusat.dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin-pusat.rtkn.index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Laporan
                            Rekapitulasi Rencana Tenaga Kerja Nasional</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Ubah Rencana Tenaga Kerja
                            Nasional</span>
                    </div>
                </li>
            </ol>
        </nav>

        <x-validation-errors class="mb-3" />
        <div class="">
            <button type="button" x-data @click="$dispatch('open-modal', 'edit-user')"
                class="inline-flex mb-3 cursor-pointer items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">
                Pratinjau Dokumen Saat Ini
            </button>

            <x-modal name="edit-user" title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                <div class="border border-gray-300 rounded-md overflow-hidden">

                    @if ($rtkn->document_path && Storage::disk('public')->exists($rtkn->document_path))
                        <iframe src="{{ Storage::url($rtkn->document_path) }}"
                            class="w-full min-h-[500px] rounded-md border"></iframe>
                    @else
                        <div class="flex items-center justify-center  min-h-[500px] text-gray-400 border rounded-md">
                            Tidak ada dokumen tersimpan
                        </div>
                    @endif
                </div>

                <x-slot:footer>
                    <button @click="$dispatch('close-modal', 'edit-user')"
                        class="inline-flex items-center justify-center  px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-100 hover:text-neutral-600 hover:bg-neutral-100">Close</button>
                </x-slot:footer>
            </x-modal>
        </div>
        <!-- Upload Form with Side-by-Side Layout -->
        <div class="bg-white rounded-md shadow-sm p-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column: Form -->
                <div>
                    <form action="{{ route('admin-pusat.rtkn.update', $rtkn->id) }}" method="POST" id="uploadForm"
                        class="space-y-8" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ $rtkn->name }}"
                                class="w-full px-4 py-2 border border-gray-300 placeholder:text-sm rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan nama dokumen Rencana Tenaga Kerja Nasional">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                RTK Acuan <span class="text-red-500">*</span>
                            </label>

                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_active" value="1"
                                        class="text-green-600 border-gray-300 focus:ring-green-500 focus:ring-2"
                                        @checked(old('is_active', $rtkn->is_active ?? 1) == 1)>

                                    <span class="text-sm text-gray-700">
                                        Ya <span class="text-gray-400">(Digunakan sebagai RTK Acuan)</span>
                                    </span>
                                </label>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_active" value="0"
                                        class="text-red-600 border-gray-300 focus:ring-red-500 focus:ring-2"
                                        @checked(old('is_active', $rtkn->is_active ?? 0) == 0)>

                                    <span class="text-sm text-gray-700">Tidak</span>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-4">
                                Anda dapat mengajukan RTK baru meskipun sudah terdapat RTK yang sedang berlaku. RTK
                                yang sedang berlaku akan digantikan secara otomatis setelah RTK baru disetujui oleh
                                Admin Pusat / Admin Provinsi.
                            </p>
                            @error('is_active')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status_verification" class="w-full rounded-md border-gray-300">
                                @foreach (\Modules\RTK\Enums\RTKStatusVerification::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('status_verification', $rtkn->status_verification->value ?? $rtkn->status_verification) == $status->value)>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_verification')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div> --}}

                        <!-- Tahun Berlaku -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Berlaku Dari Tahun <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="start_date" name="start_date" value="{{ $rtkn->start_date }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 placeholder:text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="2025">
                                @error('start_date')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sampai Tahun <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="end_date" name="end_date" value="{{ $rtkn->end_date }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 placeholder:text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="2030">
                                @error('end_date')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Dokumen Rencana Tenaga Kerja Nasional
                            </label>
                            <div class="file-upload-area rounded-md p-8 text-center cursor-pointer"
                                id="fileUploadArea">
                                <input type="file" id="fileInput" name="document_path" accept=".pdf"
                                    class="hidden">
                                <div id="uploadPrompt">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">
                                        <span class="font-semibold text-indigo-600 hover:text-indigo-500">Klik untuk
                                            upload</span>
                                        atau drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">PDF</p>
                                </div>
                                <div id="fileInfo" class="hidden">
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-left">
                                            <p id="fileName" class="text-sm font-medium text-gray-900"></p>
                                            <p id="fileSize" class="text-xs text-gray-500"></p>
                                        </div>
                                    </div>
                                    <button type="button" id="removeFileBtn"
                                        class="mt-3 px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-md hover:bg-red-200 transition-colors">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Hapus File
                                    </button>
                                </div>

                            </div>
                            <span class="text-sm text-gray-500">File maksimal berukuran 10 MB</span>
                            @error('document_path')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-4">
                            <a href="{{ route('admin-pusat.rtkn.index') }}"
                                class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-md font-medium hover:bg-gray-300 transition-colors text-center">
                                Batal
                            </a>
                            <button type="submit"
                                class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-md font-medium hover:bg-indigo-700 transition-colors">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Preview -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Dokumen Baru (Hasil Upload)
                    </label>

                    <div class="border border-indigo-300 rounded-md overflow-hidden min-h-[400px]">


                        <div class="bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700">
                            Preview Dokumen Baru
                        </div>


                        <div id="noPreview"
                            class="flex items-center justify-center h-[350px] text-center text-gray-400">
                            <div>
                                <svg class="mx-auto h-14 w-14 mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm">Belum ada dokumen baru</p>
                                <p class="text-xs mt-1">Upload file untuk melihat preview</p>
                            </div>
                        </div>

                        {{-- Preview Upload --}}
                        <div id="filePreview" class="hidden h-[700px]">
                            <iframe id="previewFrame" class="w-full h-full"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // File upload functionality
            const fileUploadArea = document.getElementById('fileUploadArea');
            const fileInput = document.getElementById('fileInput');
            const uploadPrompt = document.getElementById('uploadPrompt');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const removeFileBtn = document.getElementById('removeFileBtn');
            const noPreview = document.getElementById('noPreview');
            const filePreview = document.getElementById('filePreview');
            const previewFrame = document.getElementById('previewFrame');

            let currentFile = null;

            // Click to upload
            fileUploadArea.addEventListener('click', (e) => {
                if (!e.target.closest('#removeFileBtn')) {
                    fileInput.click();
                }
            });

            // Drag and drop
            fileUploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                fileUploadArea.classList.add('dragover');
            });

            fileUploadArea.addEventListener('dragleave', () => {
                fileUploadArea.classList.remove('dragover');
            });

            fileUploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                fileUploadArea.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelect(files[0]);
                }
            });

            // File input change
            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    handleFileSelect(file);
                }
            });

            // Remove file button
            removeFileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                removeFile();
            });

            // Handle file selection
            function handleFileSelect(file) {
                const allowedTypes = ['application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];

                if (!allowedTypes.includes(file.type)) {
                    alert('Hanya file PDF atau Word yang diperbolehkan!');
                    return;
                }

                currentFile = file;

                // Update file info
                fileName.textContent = file.name;
                fileSize.textContent = `${(file.size / 1024 / 1024).toFixed(2)} MB`;

                // Show file info, hide upload prompt
                uploadPrompt.classList.add('hidden');
                fileInfo.classList.remove('hidden');
                fileUploadArea.classList.add('has-file');

                // Show preview
                showPreview(file);
            }

            // Show preview
            function showPreview(file) {
                noPreview.classList.add('hidden');
                filePreview.classList.remove('hidden');

                if (file.type === 'application/pdf') {
                    const fileURL = URL.createObjectURL(file);
                    previewFrame.src = fileURL;
                } else {
                    previewFrame.srcdoc = `
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; background-color: #f9fafb; font-family: Inter, sans-serif;">
                        <div style="text-align: center; padding: 2rem;">
                            <svg style="width: 64px; height: 64px; margin: 0 auto; color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <h3 style="font-size: 1.125rem; font-weight: 600; color: #374151; margin-top: 1rem;">Dokumen Word</h3>
                            <p style="color: #6b7280; margin-top: 0.5rem; font-size: 0.875rem;">${file.name}</p>
                            <p style="color: #9ca3af; margin-top: 0.25rem; font-size: 0.75rem;">Preview tidak tersedia untuk file Word.<br>File akan diupload saat submit.</p>
                        </div>
                    </div>
                `;
                }
            }

            // Remove file
            function removeFile() {
                currentFile = null;
                fileInput.value = '';

                // Reset UI
                uploadPrompt.classList.remove('hidden');
                fileInfo.classList.add('hidden');
                fileUploadArea.classList.remove('has-file', 'dragover');

                // Hide preview
                noPreview.classList.remove('hidden');
                filePreview.classList.add('hidden');
                previewFrame.src = '';
            }
        </script>
    @endpush
</x-dashboard::layouts.dashboard>
