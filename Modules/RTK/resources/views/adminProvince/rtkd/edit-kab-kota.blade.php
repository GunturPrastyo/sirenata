<x-dashboard::layouts.dashboard title="Rencana Tenaga Kerja Daerah Provinsi Edit">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :items="[
            ['label' => 'Daftar Laporan RTK Kab/Kota', 'url' => route('admin-province.laporan.index')],
            ['label' => 'RTK ' . $regency->name, 'url' => route('admin-province.laporan.show-regency', $regency->code)],
            ['label' => 'Edit RTK ' . $regency->name]
        ]" />

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
        
        <div class="mb-4">
            <x-button type="button" x-data @click="$dispatch('open-modal', 'preview-document')"
                variant="secondary" icon="fas fa-file-alt">
                Pratinjau Dokumen Saat Ini
            </x-button>

            <x-modal name="preview-document" title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                <div class="border border-gray-300 rounded-md overflow-hidden">
                    @if ($rtkdp->document_path && Storage::disk('public')->exists($rtkdp->document_path))
                        <iframe src="{{ Storage::url($rtkdp->document_path) }}"
                            class="w-full min-h-[500px] rounded-md border"></iframe>
                    @else
                        <div class="flex items-center justify-center min-h-[500px] text-gray-400 border rounded-md">
                            Tidak ada dokumen tersimpan
                        </div>
                    @endif
                </div>

                <x-slot:footer>
                    <x-button variant="secondary" @click="$dispatch('close-modal', 'preview-document')">
                        Close
                    </x-button>
                </x-slot:footer>
            </x-modal>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 sm:p-10">
            <form action="{{ route('admin-province.laporan.update-regency', ['regencyCode' => $regency->code, 'rtkdp' => $rtkdp->id]) }}" method="POST" id="uploadForm" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Nama -->
                <x-form.input name="name" label="Nama" value="{{ $rtkdp->name }}" disabled />

                <!-- RTK Acuan -->
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
                    <p class="text-xs text-gray-500 mt-2">
                        Anda dapat mengajukan RTK baru meskipun sudah terdapat RTK yang sedang berlaku. RTK
                        yang sedang berlaku akan digantikan secara otomatis setelah RTK baru disetujui oleh
                        Admin Pusat / Admin Provinsi.
                    </p>

                    @error('is_active')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Berlaku -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form.input type="number" name="start_date" label="Berlaku Dari Tahun" value="{{ $rtkdp->start_date }}" disabled />
                    <x-form.input type="number" name="end_date" label="Sampai Tahun" value="{{ $rtkdp->end_date }}" disabled />
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-slate-100">
                    <x-button href="{{ route('admin-province.laporan.show-regency', $regency->code) }}"
                        variant="secondary" class="w-full sm:flex-1">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" icon="fas fa-save" class="w-full sm:flex-1">
                        Simpan
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>
