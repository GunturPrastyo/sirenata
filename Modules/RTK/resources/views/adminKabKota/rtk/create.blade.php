<x-dashboard::layouts.dashboard title="Rencana Tenaga Kerja Kabupaten/Kota Create">
    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[
            ['label' => 'Rekapitulasi Rencana Tenaga Kerja Kab/Kota', 'url' => route('admin-kab-kota.rtkd.index')],
            ['label' => 'Upload Rekapitulasi Rencana Tenaga Kerja Kab/Kota']
        ]" />

        <x-validation-errors />
        <!-- Upload Form with Side-by-Side Layout -->
        <div class="bg-white rounded-md shadow-sm p-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column: Form -->
                <div>
                    <form action="{{ route('admin-kab-kota.rtkd.store') }}" method="POST" id="uploadForm"
                        class="space-y-8" enctype="multipart/form-data">
                        @csrf
                        <!-- Nama -->
                        <x-form.input name="name" label="Nama" required placeholder="Masukkan nama dokumen Rencana Tenaga Kerja Kab/Kota" />

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                RTK Acuan <span class="text-red-500">*</span>
                            </label>

                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_active" value="1"
                                        class="text-green-600 border-gray-300 focus:ring-green-500 focus:ring-2"
                                        @checked(old('is_active', $rtkdp->is_active ?? 1) == 1)>

                                    <span class="text-sm text-gray-700">
                                        Ya <span class="text-gray-400">(Digunakan sebagai RTK Acuan)</span>
                                    </span>
                                </label>

                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_active" value="0"
                                        class="text-red-600 border-gray-300 focus:ring-red-500 focus:ring-2"
                                        @checked(old('is_active', $rtkdp->is_active ?? 0) == 0)>

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

                        <!-- Tahun Berlaku -->
                        <div class="grid grid-cols-2 gap-4">
                            <x-form.input type="number" name="start_date" label="Berlaku Dari Tahun" required placeholder="2025" />
                            <x-form.input type="number" name="end_date" label="Sampai Tahun" required placeholder="2030" />
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Dokumen Rencana Tenaga Kerja Kab/Kota <span class="text-red-500">*</span>
                            </label>
                            <div class="file-upload-area rounded-md p-8 text-center cursor-pointer" id="fileUploadArea">
                                <input type="file" id="fileInput" name="document_path" accept=".pdf" class="hidden">
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
                            <x-button href="{{ route('admin-kab-kota.rtkd.index') }}" variant="secondary" size="lg" class="flex-1">
                                Batal
                            </x-button>
                            <x-button type="submit" variant="primary" size="lg" class="flex-1">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan
                            </x-button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Preview -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Preview Dokumen
                    </label>
                    <div id="noPreview"
                        class="border-2 border-dashed border-gray-300 rounded-md h-full min-h-[400px] flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <svg class="mx-auto h-16 w-16 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm">Belum ada file yang dipilih</p>
                            <p class="text-xs mt-1">Upload file untuk melihat preview</p>
                        </div>
                    </div>
                    <div id="filePreview"
                        class="hidden border border-gray-300 rounded-md overflow-hidden h-full min-h-[400px]">
                        <iframe id="previewFrame" class="w-full h-full"></iframe>
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
