<x-dashboard::layouts.dashboard title="Manajemen Instansi (Daerah)">
    <div class="p-2 sm:p-6">
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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Instansi</span>
                    </div>
                </li>
            </ol>
        </nav>

        <x-dashboard::filter-card
            title="Daftar Instansi"
            :total="$institutions->total() . ' Instansi'"
            :resetUrl="route('super-admin.instansi.index')">

            <x-slot name="actions">
                <button type="button" x-data @click="$dispatch('open-modal', 'create-instansi')"
                    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors cursor-pointer">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden sm:inline">Tambah Instansi</span>
                    <span class="sm:hidden">Tambah</span>
                </button>
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

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs text-left">
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Nama Dinas / Instansi</th>
                            <th class="px-4 py-3 text-center w-40">Tanggal Dibuat</th>
                            <th class="px-4 py-3 text-center w-32">Status</th>
                            <th class="px-4 py-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($institutions as $key => $instansi)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 text-center text-slate-600">
                                    {{ $key + $institutions->firstItem() }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-slate-900">{{ $instansi->name }}</div>
                                </td>
                                <td class="px-4 py-3 text-center text-slate-600">
                                    {{ $instansi->created_at ? $instansi->created_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($instansi->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
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
                                </td>
                            </tr>

                            <x-modal name="edit-instansi-{{ $instansi->id }}" title="Edit Instansi">
                                <form action="{{ route('super-admin.instansi.update', $instansi->id) }}" method="POST" class="space-y-4 text-left">
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <label for="name-{{ $instansi->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                            Nama Dinas / Instansi (Daerah) <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" id="name-{{ $instansi->id }}" required
                                            value="{{ old('name', $instansi->name) }}"
                                            placeholder="Contoh: Dinas Tenaga Kerja Provinsi Jawa Timur"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm">
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_active" id="is_active-{{ $instansi->id }}" value="1"
                                            {{ $instansi->is_active ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_active-{{ $instansi->id }}" class="ml-2 block text-sm text-gray-900">
                                            Status Aktif
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 -mt-2 ml-6">Jika tidak aktif, instansi ini tidak akan muncul di pilihan registrasi user.</p>

                                    <div class="mt-6 flex justify-end gap-2 border-t pt-4">
                                        <button type="button" @click="show = false"
                                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 cursor-pointer">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </x-modal>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                    Belum ada data instansi daerah.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-200">
                {{ $institutions->links() }}
            </div>
        </x-dashboard::filter-card>
    </div>
    <x-modal name="create-instansi" title="Tambah Instansi Baru">
        <form action="{{ route('super-admin.instansi.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Dinas / Instansi (Daerah) <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" required
                    value="{{ old('name') }}"
                    placeholder="Contoh: Dinas Tenaga Kerja Provinsi Jawa Timur"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Status Aktif
                </label>
            </div>
            <p class="text-xs text-gray-500 -mt-2 ml-6">Jika tidak aktif, instansi ini tidak akan muncul di pilihan registrasi user.</p>

            <div class="mt-6 flex justify-end gap-2 border-t pt-4">
                <button type="button" @click="show = false"
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 cursor-pointer">
                    Tambah Instansi
                </button>
            </div>
        </form>
    </x-modal>
</x-dashboard::layouts.dashboard>
