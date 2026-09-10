<x-dashboard::layouts.dashboard title="Rencana Tenaga Kerja Kab/Kota">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :items="[['label' => 'Rekapitulasi Rencana Tenaga Kerja Kab/Kota']]" />

        <x-dashboard::filter-card 
            title="Daftar Dokumen Rekapitulasi Rencana Tenaga Kerja {{ auth()->user()->scopeArea?->regency?->name }}" 
            :total="$rtkds->total() . ' Dokumen RTK'"
            :resetUrl="route('admin-kab-kota.rtkd.index')">
            
            <x-slot name="actions">
                <x-button :href="route('admin-kab-kota.export-regency') . '?' . http_build_query(request()->only(['search', 'status_document', 'status_verification', 'acuan']))" variant="success" icon="fas fa-download" title="Ekspor Data">
                    <span class="hidden sm:inline">Ekspor</span>
                </x-button>
                <x-button :href="route('admin-kab-kota.rtkd.create')" variant="primary" icon="fas fa-plus">
                    <span class="hidden sm:inline">Upload RTK</span>
                    <span class="sm:hidden">Upload</span>
                </x-button>
            </x-slot>

            <x-slot name="filter_inputs">
                <!-- Status Verifikasi -->
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Status Verifikasi
                    </label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <select name="status_verification" onchange="this.form.submit()"
                            class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua</option>
                            @foreach (\Modules\RTK\Enums\RTKStatusVerification::cases() as $statusVerifikasi)
                                <option value="{{ $statusVerifikasi->value }}" @selected(request('status_verification') === $statusVerifikasi->value)>
                                    {{ $statusVerifikasi->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Status Dokumen -->
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Status Dokumen
                    </label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <select name="status_document" onchange="this.form.submit()"
                            class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua</option>
                            @foreach (\Modules\RTK\Enums\StatusDocument::cases() as $statusDocument)
                                <option value="{{ $statusDocument->value }}" @selected(request('status_document') === $statusDocument->value)>
                                    {{ $statusDocument->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- RTK Acuan -->
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        RTK Acuan
                    </label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <select name="acuan" onchange="this.form.submit()"
                            class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua</option>
                            <option value="1" @selected(request('acuan') === '1')>Ya (Acuan)</option>
                            <option value="0" @selected(request('acuan') === '0')>Tidak</option>
                        </select>
                    </div>
                </div>

                <!-- Tampilkan -->
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

                <!-- Pencarian -->
                <div class="flex-1 min-w-[240px] w-full">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Pencarian
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama RTK..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead class="bg-slate-100 border-b border-slate-200">
                    <tr class="text-slate-500 uppercase text-xs">
                        <x-table.th>No.</x-table.th>
                        <x-table.th>Dokumen RTK</x-table.th>
                        <x-table.th>Periode Berlaku</x-table.th>
                        <x-table.th>Status Verifikasi</x-table.th>
                        <x-table.th>Status Dokumen</x-table.th>
                        <x-table.th>RTK Acuan</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($rtkds as $key => $rtkd)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td>
                                <p class="text-slate-600">{{ $key + $rtkds->firstItem() }}</p>
                            </x-table.td>
                            <x-table.td>
                                <p class="text-slate-600 font-medium">{{ $rtkd->name }}</p>
                            </x-table.td>
                            <x-table.td>
                                <p class="text-slate-600">
                                    {{ $rtkd->start_date }} - {{ $rtkd->end_date }}
                                </p>
                            </x-table.td>
                            
                            <!-- Kolom Status Verifikasi -->
                            <x-table.td>
                                @if ($rtkd->status_verification === \Modules\RTK\Enums\RTKStatusVerification::APPROVED)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold text-white bg-green-500 shadow-sm">
                                        {{ $rtkd->status_verification->label() }}
                                    </span>
                                @elseif ($rtkd->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold text-white bg-amber-400 shadow-sm">
                                        {{ $rtkd->status_verification->label() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold text-red-700 bg-red-100 border border-red-200">
                                        {{ $rtkd->status_verification->label() }}
                                    </span>
                                @endif
                            </x-table.td>

                            <!-- Kolom Status Dokumen -->
                            <x-table.td>
                                @if ($rtkd->status_document === \Modules\RTK\Enums\StatusDocument::VALID)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold text-white bg-green-500 shadow-sm">
                                        {{ $rtkd->status_document->label() }}
                                    </span>
                                @elseif ($rtkd->status_document === \Modules\RTK\Enums\StatusDocument::EXPIRED)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold text-red-700 bg-red-100 border border-red-200">
                                        {{ $rtkd->status_document->label() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold text-slate-700 bg-slate-100 border border-slate-200">
                                        {{ $rtkd->status_document->label() }}
                                    </span>
                                @endif
                            </x-table.td>

                            <!-- Kolom RTK Acuan -->
                            <x-table.td>
                                @if ($rtkd->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold text-white bg-green-500 shadow-sm">
                                        Ya
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold text-slate-600 bg-slate-100 border border-slate-200">
                                        Tidak
                                    </span>
                                @endif
                            </x-table.td>

                            <x-table.td align="center">
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
                                    )
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
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="7" align="center" class="py-12">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fas fa-folder-open text-slate-300 text-3xl"></i>
                                    <p class="text-sm text-slate-500 font-medium">
                                        Tidak ada data RTKD Kab/Kota
                                    </p>
                                </div>
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table.table>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $rtkds->links() }}
            </div>
        </x-dashboard::filter-card>
    </div>

    @push('scripts')
        
    @endpush
</x-dashboard::layouts.dashboard>