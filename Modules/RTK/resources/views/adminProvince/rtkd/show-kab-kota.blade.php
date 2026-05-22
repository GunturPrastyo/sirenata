<x-dashboard::layouts.dashboard title="Validasi RTK Kab/Kota">
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
        <x-breadcrumb :items="[['label' => 'Daftar Laporan RTK Kab/Kota', 'url' => route('admin-province.laporan.index')], ['label' => 'Daftar Laporan RTK ' . $regency->name]]" />

        <x-dashboard::filter-card 
            title="Daftar Laporan RTK {{ $regency->name }}" 
            :total="$rtks->total() . ' Dokumen RTK'"
            :resetUrl="route('admin-province.laporan.show-regency', $regencyCode)">
            
            <x-slot name="actions">
                <x-button href="{{ route('admin-province.laporan.export-regency', $regencyCode) }}?{{ http_build_query(request()->only(['search', 'status_document', 'status_verification', 'acuan'])) }}"
                    variant="success" icon="fas fa-download" title="Ekspor Data" class="gap-2">
                    <span class="hidden sm:inline">Ekspor</span>
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
                        <select name="status_verification"
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
                        <select name="status_document"
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

                <!-- Filter RTK Acuan (is_active) -->
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        RTK Acuan
                    </label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <select name="acuan"
                            class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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

                <!-- Pencarian -->
                <div class="flex-1 min-w-[240px] w-full">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Pencarian
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama RTK..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th align="left">No.</x-table.th>
                        <x-table.th align="left">Dokumen RTK</x-table.th>
                        <x-table.th align="left">Periode Berlaku</x-table.th>
                        <x-table.th align="center">Status Verifikasi</x-table.th>
                        <x-table.th align="center">Status Dokumen</x-table.th>
                        <x-table.th align="center">RTK Acuan</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>

                <tbody id="admin-table-body" class="divide-y divide-slate-200">
                    @forelse ($rtks as $key => $rtk)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td align="left">
                                {{ $key + $rtks->firstItem() }}
                            </x-table.td>
                            <x-table.td align="left">
                                {{ $rtk->name }}
                            </x-table.td>
                            <x-table.td align="left">
                                {{ $rtk->start_date }} - {{ $rtk->end_date }}
                            </x-table.td>
                            <x-table.td align="center">
                                <x-badge :color="$getBadgeColor($rtk->status_verification->color())">
                                    {{ $rtk->status_verification->label() }}
                                </x-badge>
                            </x-table.td>
                            <x-table.td align="center">
                                <x-badge :color="$getBadgeColor($rtk->status_document->color())">
                                    {{ $rtk->status_document->label() }}
                                </x-badge>
                            </x-table.td>
                            <x-table.td align="center">
                                @if ($rtk->is_active)
                                    <x-badge color="success" class="gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Ya
                                    </x-badge>
                                @else
                                    <x-badge color="slate" class="gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6L18 18M6 18L18 6" />
                                        </svg>
                                        Tidak
                                    </x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td align="center">
                                <x-table.action>
                                    {{-- Step 1: Approve verifikasi — muncul kalau PENDING + is_active --}}
                                    @if($rtk->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING && $rtk->is_active)
                                        <li class="mb-2">
                                            <span class="px-3 py-2 text-center text-xs text-gray-400">Status Verifikasi</span>
                                            <div class="h-px my-1 -mx-1 bg-neutral-200"></div>
                                            <x-button type="button" x-data
                                                @click="$dispatch('open-modal', 'approve-rtk-{{ $rtk->id }}')"
                                                variant="success" class="w-full">
                                                Setujui Verifikasi
                                            </x-button>

                                            <x-modal name="approve-rtk-{{ $rtk->id }}" title="Konfirmasi Persetujuan RTK"
                                                maxWidth="sm:max-w-md">
                                                <div class="p-6 text-center">
                                                    <div class="flex justify-center mb-4">
                                                        <div
                                                            class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100">
                                                            <svg class="w-6 h-6 text-green-600"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Setujui Verifikasi
                                                        RTK</h3>
                                                    <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menyetujui
                                                        verifikasi dokumen berikut?</p>
                                                    <div
                                                        class="bg-gray-50 border rounded-md p-3 text-sm text-gray-600 mb-6">
                                                        <p class="font-medium">{{ $rtk->name }}</p>
                                                        <p class="text-xs text-gray-500">Periode {{ $rtk->start_date }} -
                                                            {{ $rtk->end_date }}</p>
                                                    </div>
                                                    <form
                                                        action="{{ route('admin-province.laporan.approveVerificationKabKota', $rtk->id) }}"
                                                        method="POST" class="flex justify-center gap-3">
                                                        @csrf
                                                        <x-button type="submit" variant="success">
                                                            Ya, Setujui
                                                        </x-button>
                                                        <x-button type="button" variant="secondary"
                                                            @click="$dispatch('close-modal', 'approve-rtk-{{ $rtk->id }}')">
                                                            Batal
                                                        </x-button>
                                                    </form>
                                                </div>
                                            </x-modal>
                                        </li>
                                    @endif

                                    {{-- Step 2: Approve dokumen — muncul kalau status_verification APPROVED +
                                    status_document NA --}}
                                    @if(
                                            $rtk->status_verification === \Modules\RTK\Enums\RTKStatusVerification::APPROVED &&
                                            $rtk->status_document === \Modules\RTK\Enums\StatusDocument::NA &&
                                            $rtk->is_active
                                        )
                                        <li class="mb-2">
                                            <span class="px-3 py-2 text-center text-xs text-gray-400">Status Dokumen</span>
                                            <div class="h-px my-1 -mx-1 bg-neutral-200"></div>
                                            <x-button type="button" x-data
                                                @click="$dispatch('open-modal', 'approve-doc-rtk-{{ $rtk->id }}')"
                                                variant="primary" class="w-full">
                                                Setujui Dokumen
                                            </x-button>

                                            <x-modal name="approve-doc-rtk-{{ $rtk->id }}"
                                                title="Konfirmasi Persetujuan Dokumen" maxWidth="sm:max-w-md">
                                                <div class="p-6 text-center">
                                                    <div class="flex justify-center mb-4">
                                                        <div
                                                            class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                                                            <svg class="w-6 h-6 text-blue-600"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Setujui Dokumen RTK
                                                    </h3>
                                                    <p class="text-sm text-gray-500 mb-4">
                                                        Dengan menyetujui dokumen ini, RTK akan menjadi
                                                        <strong>berlaku</strong>.
                                                    </p>

                                                    {{-- Warning kalau ada RTK berlaku --}}
                                                    @if($rtkBerlaku ?? false)
                                                        <div
                                                            class="bg-amber-50 border border-amber-200 rounded-md p-3 text-sm text-amber-700 mb-4 text-left">
                                                            <p class="font-medium">⚠ Perhatian</p>
                                                            <p class="text-xs mt-1">RTK <strong>{{ $rtkBerlaku->name }}</strong>
                                                                yang sedang berlaku akan otomatis diubah menjadi EXPIRED.</p>
                                                        </div>
                                                    @endif

                                                    <div
                                                        class="bg-gray-50 border rounded-md p-3 text-sm text-gray-600 mb-6">
                                                        <p class="font-medium">{{ $rtk->name }}</p>
                                                        <p class="text-xs text-gray-500">Periode {{ $rtk->start_date }} -
                                                            {{ $rtk->end_date }}</p>
                                                    </div>
                                                    <form action="{{ route('admin-province.laporan.approveDocumentKabKota', $rtk->id) }}" method="POST" class="flex justify-center gap-3">
                                                        @csrf
                                                        <x-button type="submit" variant="primary">
                                                            Ya, Setujui
                                                        </x-button>
                                                        <x-button type="button" variant="secondary"
                                                            @click="$dispatch('close-modal', 'approve-doc-rtk-{{ $rtk->id }}')">
                                                            Batal
                                                        </x-button>
                                                    </form>
                                                </div>
                                            </x-modal>
                                        </li>
                                    @endif

                                    {{-- Tolak — muncul kalau PENDING atau APPROVED tapi belum VALID, dan is_active --}}
                                    @if(
                                            $rtk->is_active && 
                                            $rtk->status_document !== \Modules\RTK\Enums\StatusDocument::VALID && 
                                            $rtk->status_verification !== \Modules\RTK\Enums\RTKStatusVerification::REJECTED
                                        )
                                        <li class="mb-2">
                                            <x-button type="button" x-data
                                                @click="$dispatch('open-modal', 'reject-rtk-{{ $rtk->id }}')"
                                                variant="danger" class="w-full">
                                                Tolak
                                            </x-button>

                                            <x-modal name="reject-rtk-{{ $rtk->id }}" title="Konfirmasi Penolakan RTK"
                                                maxWidth="sm:max-w-xl">
                                                <div class="p-6">
                                                    <div class="flex justify-center mb-4">
                                                        <div
                                                            class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                                                            <svg class="w-6 h-6 text-red-600"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Tolak
                                                        Dokumen RTK</h3>
                                                    <p class="text-sm text-gray-500 text-center mb-6">Silakan berikan alasan
                                                        penolakan dokumen berikut.</p>
                                                    <div
                                                        class="bg-gray-50 border rounded-md p-3 text-sm text-gray-600 mb-5">
                                                        <p class="font-medium">{{ $rtk->name }}</p>
                                                        <p class="text-xs text-gray-500">Periode {{ $rtk->start_date }} -
                                                            {{ $rtk->end_date }}</p>
                                                    </div>
                                                    <form action="{{ route('admin-province.laporan.rejectKabKota', $rtk->id) }}"
                                                        method="POST" class="space-y-4">
                                                        @csrf
                                                        <div>
                                                            <x-form.textarea name="reason" label="Alasan Penolakan" rows="3" required
                                                                placeholder="Contoh: Dokumen belum sesuai format yang ditetapkan" />
                                                        </div>
                                                        <div class="flex justify-end gap-3 pt-2">
                                                            <x-button type="button" variant="secondary"
                                                                @click="$dispatch('close-modal', 'reject-rtk-{{ $rtk->id }}')">
                                                                Batal
                                                            </x-button>
                                                            <x-button type="submit" variant="danger">
                                                                Ya, Tolak
                                                            </x-button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </x-modal>
                                        </li>
                                        <div class="h-px my-1 -mx-1 bg-neutral-500"></div>
                                    @endif

                                    {{-- Badge RTK Berlaku --}}
                                    @if($rtk->is_berlaku)
                                        <li>
                                            <x-badge color="success" class="w-full justify-center py-2">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                RTK Berlaku
                                            </x-badge>
                                        </li>
                                    @endif

                                    @if(
                                        (
                                            $rtk->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING &&
                                            $rtk->status_document === \Modules\RTK\Enums\StatusDocument::NA
                                        )
                                        ||
                                        (
                                            $rtk->status_verification === \Modules\RTK\Enums\RTKStatusVerification::APPROVED &&
                                            $rtk->status_document === \Modules\RTK\Enums\StatusDocument::NA
                                        )
                                        ||
                                        (
                                            $rtk->status_verification === \Modules\RTK\Enums\RTKStatusVerification::REJECTED &&
                                            $rtk->is_active  
                                            /* is_active true = masih bisa edit */
                                        )
                                    )
                                        <li class="mb-2">
                                            <a href="{{ route('admin-province.laporan.edit-regency', [$regencyCode, $rtk->id]) }}"
                                                class="inline-flex items-center cursor-pointer w-full p-2 hover:bg-slate-100 rounded text-sm">
                                                Edit RTK
                                            </a>
                                        </li>
                                    @endif

                                    <li class="mb-2">
                                        <button type="button" x-data
                                            @click="$dispatch('open-modal', 'open-document-province-{{ $rtk->id }}')"
                                            class="inline-flex items-center cursor-pointer w-full p-2 hover:bg-slate-100 rounded">
                                            Preview Dokumen RTK
                                        </button>

                                        <x-modal name="open-document-province-{{ $rtk->id }}"
                                            title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                                            <h1>{{ $rtk->name }}</h1>
                                            <div class="border border-gray-300 rounded-md overflow-hidden">
                                                @if ($rtk->document_path && Storage::disk('public')->exists($rtk->document_path))
                                                    <iframe src="{{ Storage::url($rtk->document_path) }}"
                                                        class="w-full min-h-[500px] rounded-md border"></iframe>
                                                @else
                                                    <div
                                                        class="flex items-center justify-center min-h-[500px] text-gray-400 border rounded-md">
                                                        Tidak ada dokumen tersimpan
                                                    </div>
                                                @endif
                                            </div>
                                            <x-slot:footer>
                                                <x-button variant="white"
                                                    @click="$dispatch('close-modal', 'open-document-province-{{ $rtk->id }}')">
                                                    Close
                                                </x-button>
                                            </x-slot:footer>
                                        </x-modal>
                                    </li>
                                </x-table.action>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="7" align="center" class="py-12">
                                <p class="text-sm text-slate-500">Tidak ada data RTKD Kab/Kota</p>
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table.table>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $rtks->links() }}
            </div>
        </x-dashboard::filter-card>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>
