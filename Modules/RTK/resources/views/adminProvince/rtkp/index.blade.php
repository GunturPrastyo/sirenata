<x-dashboard::layouts.dashboard title="Rencana Tenaga Kerja Daerah Provinsi">
    @php
        $getBadgeColor = function($colorClass) {
            if (str_contains($colorClass, 'yellow') || str_contains($colorClass, 'amber') || str_contains($colorClass, 'warning')) return 'warning';
            if (str_contains($colorClass, 'green') || str_contains($colorClass, 'emerald') || str_contains($colorClass, 'success')) return 'success';
            if (str_contains($colorClass, 'red') || str_contains($colorClass, 'rose') || str_contains($colorClass, 'danger')) return 'danger';
            if (str_contains($colorClass, 'blue') || str_contains($colorClass, 'indigo') || str_contains($colorClass, 'primary')) return 'indigo';
            return 'slate';
        };
    @endphp
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :items="[['label' => 'Rekapitulasi Rencana Tenaga Kerja Provinsi']]" />

        <div class="my-2">
            <x-flash-message />
        </div>

        <x-dashboard::filter-card 
            title="Daftar Rekapitulasi RTKD Provinsi" 
            :total="$rtkdps->total() . ' Dokumen RTK'"
            :resetUrl="route('admin-province.rtkdp.index')">
            
            <x-slot name="actions">
                <x-button 
                    href="{{ route('admin-province.rtkdp-export') }}?{{ http_build_query(request()->only(['search', 'status_document', 'status_verification', 'acuan'])) }}"
                    variant="success" 
                    size="md"
                    title="Ekspor Data"
                    class="gap-2"
                >
                    <i class="fas fa-download text-xs"></i>
                    <span class="hidden sm:inline">Ekspor</span>
                </x-button>
                <x-button 
                    href="{{ route('admin-province.rtkdp.create') }}"
                    variant="primary" 
                    size="md"
                    class="gap-2"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
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

            <div class="overflow-x-auto">
                <x-table.table plain>
                    <thead>
                        <tr class="text-slate-500 uppercase text-xs">
                            <x-table.th align="left">No.</x-table.th>
                            <x-table.th align="left">Dokumen RTK</x-table.th>
                            <x-table.th align="left">Periode Berlaku</x-table.th>
                            <x-table.th align="left">Status Verifikasi</x-table.th>
                            <x-table.th align="left">Status Dokumen Berlaku</x-table.th>
                            <x-table.th align="left">RTK Acuan</x-table.th>
                            <x-table.th align="center">Aksi</x-table.th>
                        </tr>
                    </thead>

                    <tbody id="admin-table-body" class="divide-y divide-slate-200">
                        @forelse ($rtkdps as $key => $rtkdp)
                            <tr class="hover:bg-slate-50 transition">
                                <x-table.td align="left">
                                    <p class="text-slate-600">{{ $key + $rtkdps->firstItem() }}</p>
                                </x-table.td>
                                <x-table.td align="left">
                                    <p class="text-slate-600">{{ $rtkdp->name }}</p>
                                </x-table.td>
                                <x-table.td align="left">
                                    <p class="text-slate-600">{{ $rtkdp->start_date }} - {{ $rtkdp->end_date }}</p>
                                </x-table.td>
                                <x-table.td align="left">
                                    <x-badge :color="$getBadgeColor($rtkdp->status_verification->color())" :text="$rtkdp->status_verification->label()" />
                                </x-table.td>
                                <x-table.td align="left">
                                    <x-badge :color="$getBadgeColor($rtkdp->status_document->color())" :text="$rtkdp->status_document->label()" />
                                </x-table.td>
                                <x-table.td align="left">
                                    @if ($rtkdp->is_active)
                                        <x-badge color="success" text="Ya" />
                                    @else
                                        <x-badge color="slate" text="Tidak" />
                                    @endif
                                </x-table.td>
                                <x-table.td align="center">
                                    <x-table.action>
                                        {{-- @if ($rtkdp->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING && $rtkdp->status_document === \Modules\RTK\Enums\StatusDocument::NA)
                                            <li>
                                                <a href="{{ route('admin-province.rtkdp.edit', $rtkdp->id) }}"
                                                    class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                    Edit
                                                </a>
                                            </li>
                                        @endif --}}

                                        {{-- Edit RTK — bisa edit kalau status_verification APPROVED + status_document NA --}}
                                        @if(
                                            (
                                                $rtkdp->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING &&
                                                $rtkdp->status_document === \Modules\RTK\Enums\StatusDocument::NA
                                            )
                                            ||
                                            (
                                                $rtkdp->status_verification === \Modules\RTK\Enums\RTKStatusVerification::APPROVED &&
                                                $rtkdp->status_document === \Modules\RTK\Enums\StatusDocument::NA
                                            )
                                            ||
                                            (
                                                $rtkdp->status_verification === \Modules\RTK\Enums\RTKStatusVerification::REJECTED &&
                                                $rtkdp->is_active  
                                                /* is_active true = masih bisa edit */
                                            )
                                        )
                                            <li class="mb-2">
                                                <a href="{{ route('admin-province.rtkdp.edit', $rtkdp->id) }}"
                                                    class="inline-flex items-center cursor-pointer w-full p-2 hover:bg-slate-100 rounded text-sm">
                                                    Edit RTK
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <a href="{{ Storage::url($rtkdp->document_path) }}"
                                                download="{{ $rtkdp->name }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Download</a>
                                        </li>

                                        <li>
                                            <button type="button" x-data
                                                @click="$dispatch('open-modal', 'open-document-province-{{ $rtkdp->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                Preview Dokumen RTK
                                            </button>

                                            <x-modal name="open-document-province-{{ $rtkdp->id }}"
                                                title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                                                <h1>{{ $rtkdp->name }}</h1>
                                                <div class="border border-gray-300 rounded-md overflow-hidden">
                                                    @if ($rtkdp->document_path && Storage::disk('public')->exists($rtkdp->document_path))
                                                        <iframe src="{{ Storage::url($rtkdp->document_path) }}"
                                                            class="w-full min-h-[500px] rounded-md border"></iframe>
                                                    @else
                                                        <div
                                                            class="flex items-center justify-center min-h-[500px] text-gray-400 border rounded-md">
                                                            Tidak ada dokumen tersimpan
                                                        </div>
                                                    @endif
                                                </div>
                                                <x-slot:footer>
                                                    <button
                                                        @click="$dispatch('close-modal', 'open-document-province-{{ $rtkdp->id }}')"
                                                        class="inline-flex items-center justify-center px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-100 hover:text-neutral-600 hover:bg-neutral-100">
                                                        Close
                                                    </button>
                                                </x-slot:footer>
                                            </x-modal>
                                        </li>
                                        @if ($rtkdp->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING && $rtkdp->status_document === \Modules\RTK\Enums\StatusDocument::NA)
                                            <li>
                                                <div
                                                    class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                    <x-modal-delete :id="$rtkdp->id"
                                                        message="Are you sure delete RTKN" :item-name="$rtkdp->name"
                                                        :route="route('admin-province.rtkdp.destroy', $rtkdp->id)" />
                                                </div>
                                            </li>
                                        @endif
                                    </x-table.action>
                                </x-table.td>
                            </tr>
                        @empty
                            <tr class="">
                                <x-table.td colspan="7" align="center" class="px-6 py-12">
                                    <p class="text-sm text-slate-500">Tidak ada data RTKD Provinsi</p>
                                </x-table.td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-table.table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $rtkdps->links() }}
            </div>
        </x-dashboard::filter-card>
    </div>
</x-dashboard::layouts.dashboard>
