<x-dashboard::layouts.dashboard title="Rekapitulasi User Provinsi">
    <div class="p-2 sm:p-6 pt-6 sm:pt-8">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[
            ['label' => 'Rekapitulasi SDM', 'url' => route('admin-pusat.rekapitulasi.index')],
            ['label' => 'Rekapitulasi User Provinsi']
        ]" />

        <div class="my-2">
            <x-flash-message />
        </div>

        <x-dashboard::filter-card 
            title="Rekapitulasi User SDM {{ $provinceName }}" 
            :total="$data->total() . ' User'"
            :resetUrl="route('admin-pusat.rekapitulasi.rekap-user-province', $provinceCode)">
            
            <x-slot name="actions">
                <x-button :href="route('admin-pusat.rekapitulasi.rekap-user-province.export', $provinceCode) . '?' . http_build_query(request()->only(['search', 'course_id']))" 
                    variant="success" size="md" icon="fas fa-download">
                    <span class="hidden sm:inline">Ekspor</span>
                </x-button>
            </x-slot>

            <x-slot name="filter_inputs">
                <!-- Per Page -->
                <div class="w-full sm:w-40">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Data per Halaman
                    </label>
                    <select name="per_page"
                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ([10, 20, 50, 100] as $page)
                            <option value="{{ $page }}" {{ request('per_page') == $page ? 'selected' : '' }}>
                                {{ $page }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Course -->
                <div class="w-full sm:w-64">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Filter Kursus
                    </label>
                    <select name="course_id"
                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kursus</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
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
                            placeholder="Cari nama user, instansi..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th>No.</x-table.th>
                        <x-table.th>Nama Lengkap User</x-table.th>
                        <x-table.th>Kursus</x-table.th>
                        <x-table.th>Instansi</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($data as $key => $row)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td>
                                {{ $data->firstItem() + $key }}
                            </x-table.td>

                            <x-table.td>
                                {{ $row->user_full_name ?? $row->user_name }}
                            </x-table.td>

                            <x-table.td>
                                {{ $row->course_name }}
                            </x-table.td>

                            <x-table.td>
                                {{ $row->instansi }}
                            </x-table.td>
                            <x-table.td>
                                <x-lms::enrollment.progress-status :status="$row->status" :progress="$row->progress" />
                            </x-table.td>

                            <x-table.td align="center">
                                <x-table.action>
                                    <a href="#"
                                        class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-slate-700 text-xs">
                                        Belum ada aksi
                                    </a>
                                </x-table.action>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="6" align="center" class="py-12">
                                Tidak ada data
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table.table>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $data->withQueryString()->links() }}
            </div>
        </x-dashboard::filter-card>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>
