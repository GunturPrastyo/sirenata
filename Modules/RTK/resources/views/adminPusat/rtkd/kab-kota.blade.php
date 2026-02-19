<x-dashboard::layouts.dashboard title="Rencana Tenaga Kerja Daerah">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
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
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Daftar Laporan RTKD Kab/Kota</span>
                    </div>
                </li>
            </ol>
        </nav>

        <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <!-- Left: Filter & Per Page -->
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <!-- Filter Status -->
                    <div class="relative w-full sm:w-48">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="status"
                            class="pl-9 pr-3 py-2.5 w-full rounded-md border border-slate-300 text-sm
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Status</option>
                            @foreach (\Modules\RTK\Enums\RTKStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                                    {{ $status->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Per Page -->
                    <div class="relative w-full sm:w-44">
                        <select name="per_page"
                            class="px-3 py-2.5 w-full rounded-md border border-slate-300 text-sm
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach ([10, 20, 50, 100] as $page)
                                <option value="{{ $page }}"
                                    {{ request('per_page') == $page ? 'selected' : '' }}>
                                    {{ $page }} / Halaman
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Right: Search + Buttons -->
                <div class="flex w-full lg:w-96 gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="pl-10 pr-4 py-2.5 w-full rounded-md border border-slate-300 text-sm
                    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Search -->
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 rounded-md
                bg-indigo-600 text-white text-sm font-medium
                hover:bg-indigo-700 transition">
                        <i class="fas fa-search text-xs"></i>
                        <span class="hidden sm:inline">Search</span>
                    </button>

                    <!-- Reset -->
                    <a href="{{ route('admin-pusat.rtkn.index') }}"
                        class="inline-flex items-center gap-2 px-4 rounded-md
                border border-slate-300 text-slate-600 text-sm font-medium
                hover:bg-slate-100 transition">
                        <i class="fas fa-rotate-left text-xs"></i>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                </div>

            </div>
        </form>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div
                class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar Laporan RTKD Kab/Kota</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total: <span class="font-medium text-slate-700" id="total-admin">{{ $rtkds->total() }}</span>
                        RTKD Kab/Kota
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md
                    text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition"
                        title="Ekspor Data">
                        <i class="fas fa-download text-xs"></i>
                        <span class="hidden sm:inline">Ekspor</span>
                    </button>

                    <button
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md
                    text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition"
                        title="Cetak">
                        <i class="fas fa-print text-xs"></i>
                        <span class="hidden sm:inline">Cetak</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">

                            <th class="px-4 md:px-6 py-3 text-left">No.</th>
                            <th class="px-4 md:px-6 py-3 text-left">Instansi (Kab/Kota)</th>
                            <th class="px-4 md:px-6 py-3 text-left">Nama Dokumen</th>
                            <th class="px-4 md:px-6 py-3 text-left">Periode Berlaku</th>
                            <th class="px-4 md:px-6 py-3 text-left">Status</th>
                            <th class="px-4 md:px-6 py-3 text-left">Aktif</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="admin-table-body" class="divide-y divide-slate-200">
                        @forelse ($rtkds as $key => $rtkd)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3 ">
                                    <p class="text-slate-600">{{ $key + $rtkds->firstItem() }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 ">
                                    <p class="text-slate-600">{{ $rtkd->regency?->name ?? '-' }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 ">
                                    <p class="text-slate-600">{{ $rtkd->name }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 ">
                                    <p class="text-slate-600">{{ $rtkd->start_date }} - {{ $rtkd->end_date }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 ">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full font-semibold {{ $rtkd->status->color() }}">
                                        {{ $rtkd->status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    @if ($rtkd->is_active)
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-200">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 6L18 18M6 18L18 6" />
                                            </svg>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        <li>
                                            <a href="{{ Storage::url($rtkd->document_path) }}"
                                                download="{{ $rtkd->name }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Download</a>
                                        </li>
                                        <li>
                                            <button type="button" x-data
                                                @click="$dispatch('open-modal', 'open-document-kab-kota-{{ $rtkd->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Lihat
                                                RTK</button>

                                            <x-modal name="open-document-kab-kota-{{ $rtkd->id }}"
                                                title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                                                <h1 class="">{{ $rtkd->name }}</h1>
                                                <div class="border border-gray-300 rounded-md overflow-hidden">

                                                    @if ($rtkd->document_path && Storage::disk('public')->exists($rtkd->document_path))
                                                        <iframe src="{{ Storage::url($rtkd->document_path) }}"
                                                            class="w-full min-h-[500px] rounded-md border"></iframe>
                                                    @else
                                                        <div
                                                            class="flex items-center justify-center  min-h-[500px] text-gray-400 border rounded-md">
                                                            Tidak ada dokumen tersimpan
                                                        </div>
                                                    @endif
                                                </div>

                                                <x-slot:footer>
                                                    <button
                                                        @click="$dispatch('close-modal', 'open-document-kab-kota-{{ $rtkd->id }}')"
                                                        class="inline-flex items-center justify-center  px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-100 hover:text-neutral-600 hover:bg-neutral-100">Close</button>
                                                </x-slot:footer>
                                            </x-modal>
                                        </li>
                                    </x-table.action>
                                </td>
                            </tr>
                        @empty
                            <tr class="">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-500">Tidak ada data RTKD Kab/Kota</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $rtkds->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>
