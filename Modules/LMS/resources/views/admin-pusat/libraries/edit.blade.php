<x-dashboard::layouts.dashboard title="Edit Materi Perpustakaan - E-Learning">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}"
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
                        <a href="{{ route('admin-pusat.libraries.index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Perpustakaan</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-8 max-w-3xl border border-gray-100">
            <div class="mb-4 sm:mb-6">
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900">Edit Materi Perpustakaan</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Perbarui informasi dan file lampiran materi
                    perpustakaan.</p>

                @if ($errors->any())
                    <div class="mt-4 bg-red-50 text-red-600 p-4 rounded-lg border border-red-200">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <form action="{{ route('admin-pusat.libraries.update', $library->id) }}" method="POST"
                enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Materi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" required value="{{ old('title', $library->title) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Judul buku / dokumen">
                    </div>

                    <div>
                        <label for="library_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Materi <span class="text-red-500">*</span>
                        </label>
                        <select id="library_type_id" name="library_type_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <option value="">Pilih Tipe</option>
                            @foreach($libraryTypes as $type)
                                <option value="{{ $type->id }}" {{ old('library_type_id', $library->library_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <label class="flex items-center cursor-pointer mt-2">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $library->is_active) ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                            </div>
                            <span class="ml-3 text-sm font-medium text-gray-700 peer-checked:text-indigo-600">Aktif
                                (Ditampilkan)</span>
                        </label>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Singkat
                        </label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Deskripsi materi...">{{ old('description', $library->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Gambar Sampul Baru <span class="text-xs text-gray-500 font-normal">(Abaikan jika tidak ingin
                                mengubah)</span>
                        </label>
                        <input type="file" name="cover_image" accept="image/*"
                            class="w-full border border-gray-300 rounded-lg p-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors mb-2">
                        @if($library->cover_image)
                            <div class="text-xs text-gray-500 mt-2">
                                Sampul saat ini: <br>
                                <img src="{{ Storage::url($library->cover_image) }}" alt="Cover"
                                    class="mt-2 h-20 rounded border">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            File Dokumen Baru <span class="text-xs text-gray-500 font-normal">(Abaikan jika tidak ingin
                                mengubah)</span>
                        </label>
                        <input type="file" name="file_path" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                            class="w-full border border-gray-300 rounded-lg p-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 transition-colors mb-2">
                        @if($library->file_path)
                            <div class="text-xs text-gray-500 mt-2">
                                File saat ini terlampir. Lampiran baru akan menggantikan file yang lama.
                            </div>
                        @endif
                    </div>

                    <div class="sm:col-span-2">
                        <label for="external_link" class="block text-sm font-medium text-gray-700 mb-2">
                            Link Eksternal
                        </label>
                        <input type="url" id="external_link" name="external_link"
                            value="{{ old('external_link', $library->external_link) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="https://...">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin-pusat.libraries.index') }}"
                        class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-medium hover:bg-gray-50 transition-colors text-center focus:ring-2 focus:ring-gray-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard::layouts.dashboard>