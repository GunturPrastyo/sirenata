<x-dashboard::layouts.dashboard title="Rencana Tenaga Kerja Kab/Kota">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a
                        href="{{ route('admin-kab-kota.dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                            ></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">
                            Rekapitulasi Rencana Tenaga Kerja Kab/Kota
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4"
        >
            <a
                href="{{ route('admin-kab-kota.rtkd.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                    />
                </svg>
                Upload RTK
            </a>
        </div>

        <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 w-full lg:flex-1">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            Status Verifikasi
                        </label>
                        <div class="relative">
                            <i
                                class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"
                            ></i>
                            <select
                                name="status_verifikasi"
                                onchange="this.form.submit()"
                                class="pl-8 pr-3 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Semua</option>
                                @foreach (\Modules\RTK\Enums\RTKStatusVerification::cases() as $statusVerifikasi)
                                    <option
                                        value="{{ $statusVerifikasi->value }}"
                                        @selected(request('status_verifikasi') === $statusVerifikasi->value)
                                    >
                                        {{ $statusVerifikasi->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            Status Dokumen
                        </label>
                        <div class="relative">
                            <i
                                class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"
                            ></i>
                            <select
                                name="status_document"
                                onchange="this.form.submit()"
                                class="pl-8 pr-3 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Semua</option>
                                @foreach (\Modules\RTK\Enums\StatusDocument::cases() as $statusDocument)
                                    <option
                                        value="{{ $statusDocument->value }}"
                                        @selected(request('status_document') === $statusDocument->value)
                                    >
                                        {{ $statusDocument->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filter RTK Acuan (is_active) --}}
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            RTK Acuan
                        </label>
                        <div class="relative">
                            <i
                                class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"
                            ></i>
                            <select
                                name="acuan"
                                onchange="this.form.submit()"
                                class="pl-8 pr-3 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Semua</option>
                                <option value="1" @selected(request('acuan') === '1')>
                                    Ya (Acuan)
                                </option>
                                <option value="0" @selected(request('acuan') === '0')>
                                    Tidak
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Per Page --}}
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            Tampilkan
                        </label>
                        <select
                            name="per_page"
                            onchange="this.form.submit()"
                            class="px-3 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            @foreach ([10, 20, 50, 100] as $page)
                                <option
                                    value="{{ $page }}"
                                    {{ request('per_page') == $page ? 'selected' : '' }}
                                >
                                    {{ $page }} / Halaman
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Search + Buttons --}}
                <div class="flex gap-2 w-full lg:w-72 shrink-0">
                    <div class="relative flex-1">
                        <i
                            class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"
                        ></i>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama RTK..."
                            class="pl-9 pr-4 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        />
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-4 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition shrink-0"
                    >
                        <i class="fas fa-search text-xs"></i>
                    </button>

                    <a
                        href="{{ route('admin-kab-kota.rtkd.index') }}"
                        class="inline-flex items-center gap-2 px-4 rounded-md border border-slate-300 text-slate-600 text-sm font-medium hover:bg-slate-100 transition shrink-0"
                    >
                        <i class="fas fa-rotate-left text-xs"></i>
                    </a>
                </div>
            </div>
        </form>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div
                class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div>
                    <h2 class="text-sm font-semibold text-slate-800">
                        Daftar Dokumen Rekapitulasi Rencana Tenaga Kerja
                        {{ auth()->user()->scopeArea?->regency?->name }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total:
                        <span class="font-medium text-slate-700" id="total-admin">
                            {{ $rtkds->total() }}
                        </span>
                        Dokumen RTK
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition"
                        title="Ekspor Data"
                    >
                        <i class="fas fa-download text-xs"></i>
                        <span class="hidden sm:inline">Ekspor</span>
                    </button>

                    <button
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition"
                        title="Cetak"
                    >
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
                            <th class="px-4 md:px-6 py-3 text-left">Dokumen RTK</th>
                            <th class="px-4 md:px-6 py-3 text-left">Periode Berlaku</th>
                            <th class="px-4 md:px-6 py-3 text-left">Status Verifikasi</th>
                            <th class="px-4 md:px-6 py-3 text-left">Status Dokumen</th>
                            <th class="px-4 md:px-6 py-3 text-left">RTK Acuan</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="admin-table-body" class="divide-y divide-slate-200">
                        @forelse ($rtkds as $key => $rtkd)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $key + $rtkds->firstItem() }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $rtkd->name }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">
                                        {{ $rtkd->start_date }} - {{ $rtkd->end_date }}
                                    </p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full font-semibold {{ $rtkd->status_verification->color() }}"
                                    >
                                        {{ $rtkd->status_verification->label() }}
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full font-semibold {{ $rtkd->status_document->color() }}"
                                    >
                                        {{ $rtkd->status_document->label() }}
                                    </span>
                                </td>

                                <td class="px-4 md:px-6 py-3">
                                    @if ($rtkd->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700"
                                        >
                                            <svg
                                                class="w-3.5 h-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7"
                                                />
                                            </svg>
                                            Ya
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500"
                                        >
                                            <svg
                                                class="w-3.5 h-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M6 6L18 18M6 18L18 6"
                                                />
                                            </svg>
                                            Tidak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        {{-- Edit RTK — bisa edit kalau status_verification APPROVED + status_document NA --}}
                                        @if (

                                            ($rtkd->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING &&
                                                $rtkd->status_document === \Modules\RTK\Enums\StatusDocument::NA) ||
                                            ($rtkd->status_verification === \Modules\RTK\Enums\RTKStatusVerification::APPROVED &&
                                                $rtkd->status_document === \Modules\RTK\Enums\StatusDocument::NA) ||
                                            ($rtkd->status_verification === \Modules\RTK\Enums\RTKStatusVerification::REJECTED &&
                                                $rtkd->status_document === \Modules\RTK\Enums\StatusDocument::NA) ||
                                            ($rtkd->status_verification === \Modules\RTK\Enums\RTKStatusVerification::REJECTED &&
                                                $rtkd->is_active)
                                            /* is_active true = masih bisa edit */                                        )
                                            <li class="mb-2">
                                                <a
                                                    href="{{ route('admin-kab-kota.rtkd.edit', $rtkd->id) }}"
                                                    class="inline-flex items-center cursor-pointer w-full p-2 hover:bg-slate-100 rounded text-sm"
                                                >
                                                    Edit RTK
                                                </a>
                                            </li>
                                        @endif

                                        <li>
                                            <a
                                                href="{{ Storage::url($rtkd->document_path) }}"
                                                download="{{ $rtkd->name }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded"
                                            >
                                                Download
                                            </a>
                                        </li>

                                        <li>
                                            <button
                                                type="button"
                                                x-data
                                                @click="$dispatch('open-modal', 'open-document-province-{{ $rtkd->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded"
                                            >
                                                Preview Dokumen RTK
                                            </button>

                                            <x-modal
                                                name="open-document-province-{{ $rtkd->id }}"
                                                title="Pratinjau Dokumen Saat Ini"
                                                maxWidth="sm:max-w-2xl"
                                            >
                                                <h1>{{ $rtkd->name }}</h1>
                                                <div
                                                    class="border border-gray-300 rounded-md overflow-hidden"
                                                >
                                                    @if ($rtkd->document_path && Storage::disk('public')->exists($rtkd->document_path))
                                                        <iframe
                                                            src="{{ Storage::url($rtkd->document_path) }}"
                                                            class="w-full min-h-[500px] rounded-md border"
                                                        ></iframe>
                                                    @else
                                                        <div
                                                            class="flex items-center justify-center min-h-[500px] text-gray-400 border rounded-md"
                                                        >
                                                            Tidak ada dokumen tersimpan
                                                        </div>
                                                    @endif
                                                </div>
                                                <x-slot:footer>
                                                    <button
                                                        @click="$dispatch('close-modal', 'open-document-province-{{ $rtkd->id }}')"
                                                        class="inline-flex items-center justify-center px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-100 hover:text-neutral-600 hover:bg-neutral-100"
                                                    >
                                                        Close
                                                    </button>
                                                </x-slot>
                                            </x-modal>
                                        </li>
                                        @if ($rtkd->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING && $rtkd->status_document === \Modules\RTK\Enums\StatusDocument::NA)
                                            <li>
                                                <div
                                                    class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded"
                                                >
                                                    <x-modal-delete
                                                        :id="$rtkd->id"
                                                        message="Are you sure delete RTKD Kab/Kota"
                                                        :item-name="$rtkd->name"
                                                        :route="route('admin-kab-kota.rtkd.destroy', $rtkd->id)"
                                                    />
                                                </div>
                                            </li>
                                        @endif
                                    </x-table.action>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-500">
                                        Tidak ada data RTKD Kab/Kota
                                    </p>
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
