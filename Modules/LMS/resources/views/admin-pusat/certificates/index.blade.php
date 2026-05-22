<x-dashboard::layouts.dashboard title="Pengaturan Sertifikat - E-Learning">
    <div class="p-2 sm:p-6">
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[['label' => 'Pengaturan Sertifikat']]" />

        <x-validation-errors />

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Pengaturan Sertifikat</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total: <span class="font-medium text-slate-700">{{ $settings->total() }}</span> Data
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <x-button type="button" x-data @click="$dispatch('open-modal', 'create-certificate-setting')" icon="fas fa-plus">
                        <span class="hidden sm:inline">Tambah Pengaturan</span>
                        <span class="sm:hidden">Tambah</span>
                    </x-button>
                </div>
            </div>

            <x-table.table plain>
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-slate-500 uppercase text-xs">
                        <x-table.th>No.</x-table.th>
                        <x-table.th>Template</x-table.th>
                        <x-table.th>Tanda Tangan</x-table.th>
                        <x-table.th>Nama Penandatangan</x-table.th>
                        <x-table.th>Jabatan</x-table.th>
                        <x-table.th align="center">Status</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($settings as $key => $setting)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td>
                                <p class="text-slate-600">{{ $key + $settings->firstItem() }}</p>
                            </x-table.td>
                            <x-table.td>
                                @if($setting->background_image)
                                    <div class="p-1 bg-gray-50 border border-dashed border-gray-300 rounded-lg inline-block">
                                        <img src="{{ Storage::url($setting->background_image) }}" alt="Template"
                                            class="h-16 w-24 object-cover rounded">
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum diunggah</span>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                @if($setting->signature_image)
                                    <div class="p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg inline-block">
                                        <img src="{{ Storage::url($setting->signature_image) }}" alt="Tanda tangan"
                                            class="h-12 object-contain">
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                <p class="text-slate-800 font-medium">{{ $setting->signer_name }}</p>
                            </x-table.td>
                            <x-table.td>
                                <p class="text-slate-600">{{ $setting->signer_title }}</p>
                            </x-table.td>
                            <x-table.td align="center">
                                @if($setting->is_active)
                                    <x-badge color="success">Aktif</x-badge>
                                @else
                                    <x-badge color="slate">Nonaktif</x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td align="center">
                                    <x-table.action>
                                        @if(!$setting->is_active)
                                        <li>
                                            <form action="{{ route('admin-pusat.certificates.activate', $setting->id) }}" method="POST" class="inline m-0 p-0">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-emerald-600 cursor-pointer">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        </li>
                                        @endif

                                        <li>
                                            <button type="button"
                                                x-data
                                                @click="$dispatch('open-modal', 'preview-certificate-{{ $setting->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-indigo-600 cursor-pointer">Preview</button>
                                        </li>

                                        <li>
                                            <button type="button"
                                                x-data
                                                @click="$dispatch('open-modal', 'edit-certificate-setting-{{ $setting->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-amber-600 cursor-pointer">Ubah</button>
                                        </li>

                                        <li>
                                            <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                <x-modal-delete :id="'delete-cert-' . $setting->id" message="Apakah Anda yakin ingin menghapus tanda tangan ini?"
                                                    :item-name="$setting->signer_name" buttonText="Hapus" buttonClass="w-full text-left text-red-600 outline-none cursor-pointer" :route="route('admin-pusat.certificates.destroy', $setting->id)" />
                                            </div>
                                        </li>
                                    </x-table.action>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="7" align="center" class="py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                <p class="text-sm text-slate-500">Belum ada data tanda tangan sertifikat.</p>
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table.table>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $settings->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <x-modal name="create-certificate-setting" title="Tambah Pengaturan Sertifikat" maxWidth="sm:max-w-md">
        <form action="{{ route('admin-pusat.certificates.store') }}" method="POST" enctype="multipart/form-data"
            x-data="{ signaturePreview: null, backgroundPreview: null }" class="space-y-4">
            @csrf

            <x-form.input name="signer_name" label="Nama Penandatangan" required placeholder="Dr. Ahmad Sutanto, M.Pd." />

            <x-form.input name="signer_title" label="Jabatan Penandatangan" required placeholder="Kepala Pusat Perencanaan Ketenagakerjaan" />

            <div>
                <x-form.input type="file" name="signature_image" label="Tanda Tangan Digital" helper="(PNG/JPG, max 2MB)" accept="image/png,image/jpg,image/jpeg" required
                    @change="signaturePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                    class="file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                <template x-if="signaturePreview">
                    <div class="mt-2 p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg inline-block">
                        <img :src="signaturePreview" alt="Preview" class="h-16 object-contain">
                    </div>
                </template>
            </div>

            <div>
                <x-form.input type="file" name="background_image" label="Template Background Sertifikat" helper="(PNG/JPG, max 5MB, disarankan 1754x1240px)" accept="image/png,image/jpg,image/jpeg" required
                    @change="backgroundPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                    class="file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                <template x-if="backgroundPreview">
                    <div class="mt-2 p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                        <img :src="backgroundPreview" alt="Preview Background" class="w-full max-h-48 object-contain rounded">
                    </div>
                </template>
            </div>

            <div class="flex gap-3 pt-2">
                <x-button type="button" x-data @click="$dispatch('close-modal', 'create-certificate-setting')" variant="white" class="flex-1">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" class="flex-1">
                    Simpan
                </x-button>
            </div>
        </form>
    </x-modal>

    @foreach($settings as $setting)
        <x-modal name="edit-certificate-setting-{{ $setting->id }}" title="Edit Pengaturan Sertifikat" maxWidth="sm:max-w-md">
            <form action="{{ route('admin-pusat.certificates.update', $setting->id) }}" method="POST" enctype="multipart/form-data"
                x-data="{ signaturePreview: null, backgroundPreview: null }" class="space-y-4">
                @csrf
                @method('PUT')

                <x-form.input name="signer_name" label="Nama Penandatangan" required placeholder="Dr. Ahmad Sutanto, M.Pd." :value="$setting->signer_name" />

                <x-form.input name="signer_title" label="Jabatan Penandatangan" required placeholder="Kepala Pusat Perencanaan Ketenagakerjaan" :value="$setting->signer_title" />

                <div>
                    @if($setting->signature_image)
                        <div class="mb-2 p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg inline-block">
                            <p class="text-[10px] text-gray-400 mb-1">Saat ini:</p>
                            <img src="{{ Storage::url($setting->signature_image) }}" alt="Tanda tangan" class="h-14 object-contain">
                        </div>
                    @endif
                    <x-form.input type="file" name="signature_image" label="Tanda Tangan Digital" helper="(kosongkan jika tidak ingin mengubah)" accept="image/png,image/jpg,image/jpeg"
                        @change="signaturePreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                        class="file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    <template x-if="signaturePreview">
                        <div class="mt-2 p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg inline-block">
                            <img :src="signaturePreview" alt="Preview" class="h-14 object-contain">
                        </div>
                    </template>
                </div>

                <div>
                    @if($setting->background_image)
                        <div class="mb-2 p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                            <p class="text-[10px] text-gray-400 mb-1">Saat ini:</p>
                            <img src="{{ Storage::url($setting->background_image) }}" alt="Template Background" class="w-full max-h-32 object-contain rounded">
                        </div>
                    @endif
                    <x-form.input type="file" name="background_image" label="Template Background Sertifikat" helper="(kosongkan jika tidak ingin mengubah)" accept="image/png,image/jpg,image/jpeg"
                        @change="backgroundPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                        class="file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    <template x-if="backgroundPreview">
                        <div class="mt-2 p-2 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
                            <img :src="backgroundPreview" alt="Preview Background" class="w-full max-h-32 object-contain rounded">
                        </div>
                    </template>
                </div>

                <div class="flex gap-3 pt-2">
                    <x-button type="button" x-data @click="$dispatch('close-modal', 'edit-certificate-setting-{{ $setting->id }}')" variant="white" class="flex-1">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" class="flex-1">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </x-modal>
    @endforeach


    @foreach($settings as $setting)
        <x-modal name="preview-certificate-{{ $setting->id }}" title="Preview Sertifikat" maxWidth="sm:max-w-4xl">
            <div class="flex flex-col items-center">

                <div class="overflow-hidden rounded shadow-sm border border-gray-200" style="width: 786px; height: 556px;">
                    <div style="transform: scale(0.7); transform-origin: top left; width: 1122px; height: 793px;">
                        @include('lms::admin-pusat.certificates.certificate-template', [
                            'nama_peserta' => 'Nama Peserta Contoh',
                            'nama_kursus' => 'Perencanaan Tenaga Kerja Makro',
                            'tanggal_selesai' => now()->translatedFormat('d F Y'),
                            'nomor_sertifikat' => 'CERT-' . date('Y') . '-PTK-001',
                            'background_url' => $setting->background_image ? Storage::url($setting->background_image) : null,
                            'signature_url' => $setting->signature_image ? Storage::url($setting->signature_image) : null,
                            'signer_name' => $setting->signer_name,
                            'signer_title' => $setting->signer_title,
                        ])
                    </div>
                </div>

                <div class="flex justify-end w-full mt-4 -mb-5">
                    <x-button type="button" x-data @click="$dispatch('close-modal', 'preview-certificate-{{ $setting->id }}')" variant="white">
                        Tutup
                    </x-button>
                </div>
            </div>
        </x-modal>
    @endforeach

</x-dashboard::layouts.dashboard>
