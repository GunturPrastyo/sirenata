<x-dashboard::layouts.dashboard title="Proyek - E-Learning">
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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Proyek</span>
                    </div>
                </li>
            </ol>
        </nav>


        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ showFilter: {{ (request('search') || request('status')) ? 'true' : 'false' }} }">
            <div
                class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar Proyek</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total: <span class="font-medium text-slate-700" id="total-admin">{{ $projects->total() }}</span>
                        Proyek
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" @click="showFilter = !showFilter"
                        class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-slate-700 bg-white text-sm font-medium rounded-md hover:bg-slate-50 transition-colors cursor-pointer"
                        :class="showFilter ? 'bg-slate-100 border-slate-400 font-semibold' : ''">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    <a href="{{ route('admin-pusat.project.export') }}?{{ http_build_query(request()->only(['search', 'status'])) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors"
                        title="Ekspor Data">
                        <i class="fas fa-download text-xs"></i>
                        <span class="hidden sm:inline">Ekspor</span>
                    </a>
                    @can('project-create')
                        <a href="{{ route($routePrefix . 'create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors cursor-pointer">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline">Tambah Proyek</span>
                            <span class="sm:hidden">Tambah</span>
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Search Bar inside Card -->
            <form method="GET" class="p-5 border-b border-slate-200 bg-slate-50/50" x-show="showFilter" x-transition @if(!(request('search') || request('status'))) style="display: none;" @endif>
                <div class="flex flex-col sm:flex-row flex-wrap gap-4 items-end">
                    <!-- Status -->
                    <div class="w-full sm:w-48">
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            Filter Status
                        </label>
                        <div class="relative">
                            <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <select name="status" class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Status</option>
                                @foreach (['On Progress', 'Completed', 'Draft'] as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Per Page -->
                    <div class="w-full sm:w-40">
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            Data per Halaman
                        </label>
                        <select name="per_page" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach ([10, 20, 50, 100] as $page)
                                <option value="{{ $page }}" {{ request('per_page') == $page ? 'selected' : '' }}>
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
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama proyek..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2 w-full sm:w-auto justify-end">
                        <button type="submit" title="Cari" class="w-[42px] h-[42px] inline-flex justify-center items-center rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route($routePrefix . 'index') }}" title="Reset" class="w-[42px] h-[42px] inline-flex justify-center items-center rounded-lg border border-slate-300 text-slate-600 text-sm font-medium hover:bg-slate-100 transition">
                            <i class="fas fa-rotate-left"></i>
                        </a>
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">
                            <th class="px-4 md:px-6 py-3 text-left">No.</th>
                            <th class="px-4 md:px-6 py-3 text-left">Nama</th>
                            <th class="px-4 md:px-6 py-3 text-left">Tipe</th>
                            <th class="px-4 md:px-6 py-3 text-left">Periode</th>
                            <th class="px-4 md:px-6 py-3 text-left">Ketua Tim</th>
                            <th class="px-4 md:px-6 py-3 text-left">Persentase</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="admin-table-body" class="divide-y divide-slate-200">
                        @forelse ($projects as $key => $project)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $key + $projects->firstItem() }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600 font-medium">{{ $project->name }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <span
                                        class="px-2 py-1 bg-slate-100 text-slate-800 border border-slate-200 rounded text-xs">{{ $project->type }}</span>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">
                                        {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : '-' }}
                                        -
                                        {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : '-' }}
                                    </p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $project->leader->name ?? '-' }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <div class="flex items-center">
                                        <span
                                            class="text-indigo-600 font-medium whitespace-nowrap mr-2">{{ $project->progress ?? 0 }}%</span>
                                        <div class="w-24 lg:w-32 bg-slate-200 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full"
                                                style="width: {{ $project->progress ?? 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        @can('project-view')
                                            <li>
                                                <a href="{{ route($routePrefix . 'show', $project->id) }}"
                                                    class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-blue-600">Detail</a>
                                            </li>
                                        @endcan

                                        @can('project-edit')
                                            <li>
                                                <a href="{{ route($routePrefix . 'edit', $project->id) }}"
                                                    class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-amber-600">Ubah</a>
                                            </li>
                                        @endcan

                                        @can('project-delete')
                                            <li>
                                                <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                    <x-modal-delete :id="'delete-project-' . $project->id" message="Apakah Anda yakin ingin menghapus proyek ini?"
                                                        :item-name="$project->name" buttonText="Hapus" buttonClass="w-full text-left text-red-600 outline-none cursor-pointer" :route="route($routePrefix . 'destroy', $project->id)" />
                                                </div>
                                            </li>
                                        @endcan
                                    </x-table.action>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-500">Tidak ada proyek yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $projects->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    @include('project::partials.index-modal')

    @push('scripts')
        @include('project::partials.index-scripts')
    @endpush
</x-dashboard::layouts.dashboard>