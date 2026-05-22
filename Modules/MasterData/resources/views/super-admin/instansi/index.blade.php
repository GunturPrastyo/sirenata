<x-dashboard::layouts.dashboard title="Manajemen Instansi (Daerah)">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :items="[['label' => 'Instansi']]" />

        <x-dashboard::filter-card
            title="Daftar Instansi"
            :total="$institutions->total() . ' Instansi'"
            :resetUrl="route('super-admin.instansi.index')">

            <x-slot name="actions">
                <x-button type="button" x-data @click="$dispatch('open-modal', 'create-instansi')" size="sm" icon="fas fa-plus">
                    <span class="hidden sm:inline">Tambah Instansi</span>
                    <span class="sm:hidden">Tambah</span>
                </x-button>
            </x-slot>

            <x-slot name="filter_inputs">
                <!-- Search -->
                <div class="flex-1 min-w-[240px] w-full">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Pencarian
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama instansi..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th align="center" class="w-12">No</x-table.th>
                        <x-table.th>Nama Dinas / Instansi</x-table.th>
                        <x-table.th align="center" class="w-40">Tanggal Dibuat</x-table.th>
                        <x-table.th align="center" class="w-32">Status</x-table.th>
                        <x-table.th align="center" class="w-24">Aksi</x-table.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($institutions as $key => $instansi)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td align="center">
                                <p class="text-slate-600">{{ $key + $institutions->firstItem() }}</p>
                            </x-table.td>
                            <x-table.td>
                                <div class="text-sm font-semibold text-slate-900">{{ $instansi->name }}</div>
                            </x-table.td>
                            <x-table.td align="center">
                                <p class="text-slate-600">{{ $instansi->created_at ? $instansi->created_at->format('d M Y H:i') : '-' }}</p>
                            </x-table.td>
                            <x-table.td align="center">
                                <x-badge color="{{ $instansi->is_active ? 'emerald' : 'red' }}" text="{{ $instansi->is_active ? 'Aktif' : 'Tidak Aktif' }}" />
                            </x-table.td>
                            <x-table.td align="center">
                                <x-table.action>
                                    <li>
                                        <button type="button" x-data @click="$dispatch('open-modal', 'edit-instansi-{{ $instansi->id }}')"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-amber-600 cursor-pointer text-left">Ubah</button>
                                    </li>
                                    <li>
                                        <form action="{{ route('super-admin.instansi.toggle-status', $instansi->id) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-slate-600 text-left cursor-pointer">
                                                {{ $instansi->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                             </button>
                                        </form>
                                    </li>
                                    <li>
                                        <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                            <x-modal-delete :id="'delete-instansi-' . $instansi->id" message="Apakah Anda yakin ingin menghapus instansi ini?"
                                                :item-name="$instansi->name" buttonText="Hapus" buttonClass="w-full text-left text-red-600 outline-none cursor-pointer" :route="route('super-admin.instansi.destroy', $instansi->id)" />
                                        </div>
                                    </li>
                                </x-table.action>
                            </x-table.td>
                        </tr>

                        <x-modal name="edit-instansi-{{ $instansi->id }}" title="Edit Instansi">
                            <form action="{{ route('super-admin.instansi.update', $instansi->id) }}" method="POST" class="space-y-4 text-left">
                                @csrf
                                @method('PUT')

                                <x-form.input name="name" id="name-{{ $instansi->id }}" label="Nama Dinas / Instansi (Daerah)" value="{{ old('name', $instansi->name) }}" required placeholder="Contoh: Dinas Tenaga Kerja Provinsi Jawa Timur" />

                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active-{{ $instansi->id }}" value="1"
                                        {{ $instansi->is_active ? 'checked' : '' }}
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_active-{{ $instansi->id }}" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                                        Status Aktif
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 -mt-2 ml-6">Jika tidak aktif, instansi ini tidak akan muncul di pilihan registrasi user.</p>

                                <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-4">
                                    <x-button type="button" @click="show = false" variant="white">
                                        Batal
                                    </x-button>
                                    <x-button type="submit">
                                        Simpan Perubahan
                                    </x-button>
                                </div>
                            </form>
                        </x-modal>
                    @empty
                        <tr>
                            <x-table.td colspan="5" align="center" class="py-8">
                                <p class="text-slate-500">Belum ada data instansi daerah.</p>
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table.table>
            
            <div class="p-4 border-t border-gray-200">
                {{ $institutions->links() }}
            </div>
        </x-dashboard::filter-card>
    </div>
    <x-modal name="create-instansi" title="Tambah Instansi Baru">
        <form action="{{ route('super-admin.instansi.store') }}" method="POST" class="space-y-4">
            @csrf

            <x-form.input name="name" id="name" label="Nama Dinas / Instansi (Daerah)" value="{{ old('name') }}" required placeholder="Contoh: Dinas Tenaga Kerja Provinsi Jawa Timur" />

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                    Status Aktif
                </label>
            </div>
            <p class="text-xs text-gray-500 -mt-2 ml-6">Jika tidak aktif, instansi ini tidak akan muncul di pilihan registrasi user.</p>

            <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-4">
                <x-button type="button" @click="show = false" variant="white">
                    Batal
                </x-button>
                <x-button type="submit">
                    Tambah Instansi
                </x-button>
            </div>
        </form>
    </x-modal>
</x-dashboard::layouts.dashboard>
