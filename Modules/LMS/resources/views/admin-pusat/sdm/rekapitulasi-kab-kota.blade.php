<x-dashboard::layouts.dashboard title="Rekapitulasi Kabupaten/Kota">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[
            ['label' => 'Rekapitulasi Provinsi', 'url' => route('admin-pusat.rekapitulasi.index')],
            ['label' => 'Rekapitulasi Kabupaten/Kota']
        ]" />

        <div class="my-2">
            <x-flash-message />
        </div>

        <x-dashboard::filter-card 
            title="Rekapitulasi SDM Kabupaten/Kota" 
            :total="$data->total() . ' Kabupaten/Kota'"
            :resetUrl="route('admin-pusat.rekapitulasi.kab-kota', $provinceCode)">
            
            <x-slot name="filter_inputs">
                <!-- Per Page -->
                <div class="w-full sm:w-44">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Data per Halaman
                    </label>
                    <select name="per_page"
                        class="px-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm
                        focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ([10, 20, 50, 100] as $page)
                            <option value="{{ $page }}"
                                {{ request('per_page') == $page ? 'selected' : '' }}>
                                {{ $page }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pencarian -->
                <div class="flex-1 min-w-[240px] w-full">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Pencarian
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama kabupaten/kota..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th>No.</x-table.th>
                        <x-table.th>Nama Kab/Kota</x-table.th>
                        <x-table.th>Jumlah User</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>

                <tbody id="admin-table-body" class="divide-y divide-slate-200">
                    @forelse ($data as $key => $item)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td>
                                <p class="text-slate-600">{{ $key + $data->firstItem() }}</p>
                            </x-table.td>
                            <x-table.td>
                                <p class="text-slate-600">{{ $item->name }}</p>
                            </x-table.td>
                            <x-table.td>
                                <p class="text-slate-600 ">{{ $item->total_users }}</p>
                            </x-table.td>

                            <x-table.td align="center">
                                <x-table.action>
                                    <li>
                                        <a href="{{ route('admin-pusat.rekapitulasi.rekap-user-kab-kota', $item->code) }}"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                            Rekapitulasi User
                                        </a>
                                    </li>
                                </x-table.action>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="4" align="center" class="py-12">
                                <p class="text-sm text-slate-500">Tidak ada data Rekapitulasi</p>
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table.table>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $data->links() }}
            </div>
        </x-dashboard::filter-card>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>
