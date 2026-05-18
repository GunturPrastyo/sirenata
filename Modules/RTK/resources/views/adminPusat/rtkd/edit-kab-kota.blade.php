<x-dashboard::layouts.dashboard title="Rencana Tenaga Kerja Daerah Provinsi Edit">
    <div class="p-2 sm:p-6">
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
                        <a href="{{ route('admin-pusat.rtkd.index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Daftar Laporan
                            RTKD Provinsi</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin-pusat.rtkd.kab-kota', $regency->province_code) }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Daftar Laporan
                            RTK
                            {{ $province->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('admin-pusat.rtkd.show-regency', $regency->code) }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Daftar Laporan RTK {{ $regency->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit RTKD
                            {{ $regency->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if ($rtkdp->status_verification === \Modules\RTK\Enums\RTKStatusVerification::REJECTED)
            <div class="mb-6 border border-red-200 bg-red-50 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3m0 3h.01M21 12a9 9 0 11-18 0a9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-red-700">
                            Dokumen RTK Ditolak
                        </p>
                        <p class="text-sm text-red-600 mt-1">
                            Dokumen yang Anda ajukan ditolak oleh
                            {{ $rtkdp->approver?->name ?? 'Admin Pusat/Admin Province' }}.
                            Silakan perbaiki dokumen berdasarkan alasan berikut:
                        </p>
                        <div class="mt-3 bg-white border border-red-200 rounded-md p-3 text-sm text-gray-700">
                            {{ $rtkdp->rejected_reason }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <x-validation-errors class="mb-3" />
        <div class="">
            <button type="button" x-data @click="$dispatch('open-modal', 'preview-document')"
                class="inline-flex mb-3 cursor-pointer items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">
                Pratinjau Dokumen Saat Ini
            </button>

            <x-modal name="preview-document" title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                <div class="border border-gray-300 rounded-md overflow-hidden">

                    @if ($rtkdp->document_path && Storage::disk('public')->exists($rtkdp->document_path))
                        <iframe src="{{ Storage::url($rtkdp->document_path) }}"
                            class="w-full min-h-[500px] rounded-md border"></iframe>
                    @else
                        <div class="flex items-center justify-center  min-h-[500px] text-gray-400 border rounded-md">
                            Tidak ada dokumen tersimpan
                        </div>
                    @endif
                </div>

                <x-slot:footer>
                    <button @click="$dispatch('close-modal', 'preview-document')"
                        class="inline-flex items-center justify-center  px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-100 hover:text-neutral-600 hover:bg-neutral-100">Close</button>
                </x-slot:footer>
            </x-modal>
        </div>
        <!-- Upload Form with Side-by-Side Layout -->
        <div class="bg-white rounded-md shadow-sm p-10">
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
                <!-- Left Column: Form -->
                <div>
                    <form action="{{ route('admin-pusat.rtkd.update-regency', ['regencyCode' => $regency->code, 'rtkdp' => $rtkdp->id]) }}" method="POST" id="uploadForm" class="space-y-8" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama 
                            </label>
                            <input type="text" id="name" name="name" value="{{ $rtkdp->name }}" disabled
                                class="w-full px-4 py-2 cursor-not-allowed border border-gray-300 rounded-md focus:ring-2 placeholder:text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan nama dokumen Rencana Tenaga Kerja Daerah Provinsi">
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
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Berlaku Dari Tahun 
                                </label>
                                <input type="number" id="start_date" name="start_date" value="{{ $rtkdp->start_date }}" disabled
                                    class="w-full px-4 py-2 border cursor-not-allowed  border-gray-300 rounded-md  placeholder:text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="2025">
                                @error('start_date')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sampai Tahun 
                                </label>
                                <input type="number" id="end_date" name="end_date" value="{{ $rtkdp->end_date }}" disabled
                                    class="w-full px-4 py-2 border cursor-not-allowed border-gray-300 rounded-md focus:ring-2  placeholder:text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="2030">
                                @error('end_date')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-4">
                            <a href="{{ route('admin-pusat.rtkd.show-province', $provinceCode) }}"
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
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>
