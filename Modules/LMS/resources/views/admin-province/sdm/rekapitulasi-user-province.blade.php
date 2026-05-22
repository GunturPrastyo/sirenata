<x-dashboard::layouts.dashboard title="Rekapitulasi User Provinsi">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-province.dashboard') }}"
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
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Rekapitulasi User Provinsi</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="my-2">
            <x-flash-message />
        </div>

        <x-dashboard::filter-card 
            title="Rekapitulasi User SDM {{ $provinceName }}" 
            :total="$data->total() . ' User'"
            :resetUrl="route('admin-province.rekapitulasi.rekap-user-province')">
            
            <x-slot name="actions">
                <a href="{{ route('admin-province.rekapitulasi.rekap-user-province.export') }}?{{ http_build_query(request()->only(['search', 'course_id'])) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors"
                    title="Ekspor Data">
                    <i class="fas fa-download text-xs"></i>
                    <span class="hidden sm:inline">Ekspor</span>
                </a>
            </x-slot>

            <x-slot name="filter_inputs">
                <!-- Per Page -->
                <div class="w-full sm:w-40">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Data per Halaman
                    </label>
                    <select name="per_page" onchange="this.form.submit()"
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
                    <select name="course_id" onchange="this.form.submit()"
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

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">
                            <th class="px-4 md:px-6 py-3 text-left">No.</th>
                            <th class="px-4 md:px-6 py-3 text-left">Nama Lengkap User</th>
                            <th class="px-4 md:px-6 py-3 text-left">Kursus</th>
                            <th class="px-4 md:px-6 py-3 text-left">Instansi</th>
                            <th class="px-4 md:px-6 py-3 text-left">Status</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($data as $key => $row)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3">
                                    {{ $data->firstItem() + $key }}
                                </td>

                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $row->user_name }}</p>
                                </td>

                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $row->course_name }}</p>
                                </td>

                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $row->instansi }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <x-lms::enrollment.progressstatus :status="$row->status" :progress="$row->progress" />
                                </td>

                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        <a href="#"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                            Belum ada aksi
                                        </a>
                                    </x-table.action>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $data->withQueryString()->links() }}
            </div>
        </x-dashboard::filter-card>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>
