<x-dashboard::layouts.dashboard title="Tanda Tangan Sertifikat - E-Learning">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Tanda Tangan Sertifikat</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if ($errors->any())
            <div class="mt-2 mb-4 bg-red-50 text-red-600 p-4 rounded-lg border border-red-200">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar Tanda Tangan Sertifikat</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total: <span class="font-medium text-slate-700">{{ $settings->total() }}</span> Data
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" x-data @click="$dispatch('open-modal', 'create-certificate-setting')"
                        class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Tambah Tanda Tangan</span>
                        <span class="sm:hidden">Tambah</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">
                            <th class="px-4 md:px-6 py-3 text-left">No.</th>
                            <th class="px-4 md:px-6 py-3 text-left">Tanda Tangan</th>
                            <th class="px-4 md:px-6 py-3 text-left">Nama Penandatangan</th>
                            <th class="px-4 md:px-6 py-3 text-left">Jabatan</th>
                            <th class="px-4 md:px-6 py-3 text-center">Status</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($settings as $key => $setting)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $key + $settings->firstItem() }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    @if($setting->signature_image)
                                        <div class="p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg inline-block">
                                            <img src="{{ Storage::url($setting->signature_image) }}" alt="Tanda tangan"
                                                class="h-12 object-contain">
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-800 font-medium">{{ $setting->signer_name }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $setting->signer_title }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    @if($setting->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <form action="{{ route('admin-pusat.certificates.activate', $setting->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-50 text-slate-500 border border-slate-200 rounded-full text-xs font-medium hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition cursor-pointer"
                                                title="Klik untuk mengaktifkan">
                                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                                Nonaktif
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        <li>
                                            <button type="button"
                                                x-data
                                                @click="$dispatch('open-modal', 'edit-certificate-setting-{{ $setting->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-indigo-600 cursor-pointer">Edit</button>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin-pusat.certificates.destroy', $setting->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus tanda tangan ini?');"
                                                class="inline-flex items-center w-full hover:bg-slate-100 rounded m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full text-left p-2 text-red-600">Hapus</button>
                                            </form>
                                        </li>
                                    </x-table.action>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                    <p class="text-sm text-slate-500">Belum ada data tanda tangan sertifikat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $settings->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    {{-- Modal: Tambah Tanda Tangan Sertifikat --}}
    <x-modal name="create-certificate-setting" title="Tambah Tanda Tangan Sertifikat">
        <form action="{{ route('admin-pusat.certificates.store') }}" method="POST" enctype="multipart/form-data"
            x-data="{ signaturePreview: null }" class="space-y-4">
            @csrf
            <div>
                <label for="create-signer-name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Penandatangan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="create-signer-name" name="signer_name" required value="{{ old('signer_name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                    placeholder="Dr. Ahmad Sutanto, M.Pd.">
            </div>

            <div>
                <label for="create-signer-title" class="block text-sm font-medium text-gray-700 mb-1">
                    Jabatan Penandatangan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="create-signer-title" name="signer_title" required value="{{ old('signer_title') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                    placeholder="Kepala Pusat Perencanaan Ketenagakerjaan">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanda Tangan Digital <span class="text-red-500">*</span>
                    <span class="text-xs text-gray-500 font-normal">(PNG/JPG, max 2MB)</span>
                </label>
                <input type="file" name="signature_image" accept="image/png,image/jpg,image/jpeg" required
                    @change="signaturePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                    class="w-full border border-gray-300 rounded-lg p-1 text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <template x-if="signaturePreview">
                    <div class="mt-2 p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg inline-block">
                        <img :src="signaturePreview" alt="Preview" class="h-16 object-contain">
                    </div>
                </template>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" x-data @click="$dispatch('close-modal', 'create-certificate-setting')"
                    class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm text-center cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors text-sm cursor-pointer">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>

    {{-- Modal: Edit Tanda Tangan Sertifikat (satu modal per item) --}}
    @foreach($settings as $setting)
        <x-modal name="edit-certificate-setting-{{ $setting->id }}" title="Edit Tanda Tangan Sertifikat">
            <form action="{{ route('admin-pusat.certificates.update', $setting->id) }}" method="POST" enctype="multipart/form-data"
                x-data="{ signaturePreview: null }" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit-signer-name-{{ $setting->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Penandatangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit-signer-name-{{ $setting->id }}" name="signer_name" required
                        value="{{ old('signer_name', $setting->signer_name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                        placeholder="Dr. Ahmad Sutanto, M.Pd.">
                </div>

                <div>
                    <label for="edit-signer-title-{{ $setting->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                        Jabatan Penandatangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit-signer-title-{{ $setting->id }}" name="signer_title" required
                        value="{{ old('signer_title', $setting->signer_title) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                        placeholder="Kepala Pusat Perencanaan Ketenagakerjaan">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanda Tangan Digital
                        <span class="text-xs text-gray-500 font-normal">(kosongkan jika tidak ingin mengubah)</span>
                    </label>
                    @if($setting->signature_image)
                        <div class="mb-2 p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg inline-block">
                            <p class="text-[10px] text-gray-400 mb-1">Saat ini:</p>
                            <img src="{{ Storage::url($setting->signature_image) }}" alt="Tanda tangan" class="h-14 object-contain">
                        </div>
                    @endif
                    <input type="file" name="signature_image" accept="image/png,image/jpg,image/jpeg"
                        @change="signaturePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                        class="w-full border border-gray-300 rounded-lg p-1 text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <template x-if="signaturePreview">
                        <div class="mt-2 p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg inline-block">
                            <img :src="signaturePreview" alt="Preview" class="h-14 object-contain">
                        </div>
                    </template>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" x-data @click="$dispatch('close-modal', 'edit-certificate-setting-{{ $setting->id }}')"
                        class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm text-center cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors text-sm cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-dashboard::layouts.dashboard>
