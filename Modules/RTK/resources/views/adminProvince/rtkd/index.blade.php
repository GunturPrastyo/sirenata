<x-dashboard::layouts.dashboard title="Rencana Tenaga Kerja Daerah ">
    @php
        // Helper warna jika ingin dipakai global
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
        <x-breadcrumb :items="[['label' => 'RTK Daerah Kabupaten/Kota']]" />

        <x-dashboard::filter-card 
            title="Daftar Laporan RTKD Kab/Kota - Provinsi {{ auth()->user()->scopeArea?->province->name }}" 
            :total="$rtkds->total() . ' Kab/Kota'"
            :resetUrl="route('admin-province.laporan.index')">
            
            <x-slot name="actions">
                <x-button 
                    href="{{ route('admin-province.laporan.export-regency-by-province') }}?{{ http_build_query(request()->only(['search', 'status_verification', 'status_document', 'acuan'])) }}"
                    variant="success" 
                    size="md"
                    title="Ekspor Data"
                    class="gap-2"
                >
                    <i class="fas fa-download text-xs"></i>
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
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama instansi..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th align="center" class="w-16">No</x-table.th>
                        <x-table.th align="left" class="w-48">Instansi (Kab/Kota)</x-table.th>
                        {{-- Lebar kolom Nama Dokumen diperbesar --}}
                        <x-table.th align="left" class="min-w-[280px]">Nama Dokumen</x-table.th>
                        <x-table.th align="left" class="w-40">Periode Berlaku</x-table.th>
                        <x-table.th align="center" class="w-36">Status Verifikasi</x-table.th>
                        <x-table.th align="center" class="w-48">Status Berlaku Dokumen</x-table.th>
                        <x-table.th align="left" class="w-40">Disetujui Oleh</x-table.th>
                        <x-table.th align="left" class="w-40">Tanggal Diverifikasi</x-table.th>
                        <x-table.th align="center" class="w-24">Aksi</x-table.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse ($rtkds as $key => $regency)
                        @php
                            $verifColor = 'slate';
                            $docColor = 'slate';
                            $verifLabel = '';
                            $docLabel = '';
                            $valDoc = '';

                            if ($regency->latest_rtk) {
                                // Ekstraksi label asli dari Enum
                                $verifLabel = $regency->latest_rtk->status_verification->label() ?? '';
                                $docLabel = $regency->latest_rtk->status_document->label() ?? '';

                                // Ambil value raw untuk logic penentuan warna
                                $valVerif = strtolower($regency->latest_rtk->status_verification->value ?? '');
                                $valDoc = strtolower($regency->latest_rtk->status_document->value ?? '');

                                // Evaluasi penentuan warna Badge
                                $verifColor = match($valVerif) {
                                    'approved' => 'success', // Disetujui -> Hijau
                                    'pending' => 'warning',  // Menunggu Persetujuan -> Kuning
                                    'rejected' => 'danger',
                                    default => 'slate',
                                };

                                $docColor = match($valDoc) {
                                    'valid' => 'success',    // Berlaku -> Hijau
                                    'expired' => 'danger',
                                    default => 'slate',
                                };
                            }
                        @endphp

                        <tr>
                            <x-table.td align="center">
                                {{ $key + $rtkds->firstItem() }}
                            </x-table.td>

                            {{-- Nama Provinsi + Tooltip pending_rtk_count --}}
                            <x-table.td align="left">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-slate-700">{{ $regency->name }}</p>

                                    @if(($regency->pending_rtk_count ?? 0) > 0)
                                        <div class="relative group">
                                            <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-amber-500 rounded-full cursor-pointer">
                                                {{ $regency->pending_rtk_count }}
                                            </span>
                                            {{-- Tooltip --}}
                                            <div class="absolute left-6 top-0 z-20 hidden group-hover:block w-52 bg-gray-800 text-white text-xs rounded-md px-3 py-2 shadow-lg whitespace-normal">
                                                Ada {{ $regency->pending_rtk_count }} RTK yang menunggu persetujuan
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </x-table.td>

                            {{-- Nama Dokumen dengan Label RTK Acuan diturunkan ke bawah teks --}}
                            <x-table.td align="left">
                                @if ($regency->latest_rtk)
                                    <div class="flex flex-col items-start gap-1.5 py-1">
                                        <p class="font-semibold text-slate-800 leading-snug">{{ $regency->latest_rtk->name }}</p>
                                        
                                        @if($regency->latest_rtk->is_berlaku || $regency->latest_rtk->is_active)
                                            <div class="mt-0.5">
                                                <x-badge color="indigo" class="gap-1">
                                                    <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    RTK Acuan
                                                </x-badge>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-sm">Belum ada dokumen</span>
                                @endif
                            </x-table.td>

                            {{-- Periode --}}
                            <x-table.td align="left">
                                @if ($regency->latest_rtk)
                                    <span class="text-slate-600 font-medium">
                                        {{ $regency->latest_rtk->start_date }}
                                        <span class="mx-1 text-slate-400">–</span>
                                        {{ $regency->latest_rtk->end_date }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </x-table.td>

                            {{-- Status Verifikasi --}}
                            <x-table.td align="center">
                                @if ($regency->latest_rtk)
                                    <x-badge :color="$verifColor" :text="$verifLabel" />
                                @else
                                    <x-badge color="slate" text="Belum ada verifikasi" />
                                @endif
                            </x-table.td>

                            {{-- Status Berlaku Dokumen --}}
                            <x-table.td align="center">
                                @if ($regency->latest_rtk)
                                    @if($valDoc !== 'na')
                                        <x-badge :color="$docColor" :text="$docLabel" />
                                    @else
                                        <x-badge color="slate" text="N/A" />
                                    @endif
                                @else
                                    <x-badge color="slate" text="Belum ada status dokumen" />
                                @endif
                            </x-table.td>

                            <x-table.td align="left">
                                {{ $regency->latest_rtk?->display_name_approver ?? '-' }}
                            </x-table.td>

                            {{-- Tanggal Diverifikasi --}}
                            <x-table.td align="left">
                                {{ $regency->latest_rtk?->approved_at?->format('d M Y') ?? '-' }}
                            </x-table.td>

                            {{-- Aksi --}}
                            <x-table.td align="center">
                                <x-table.action>
                                    <li>
                                        <a href="{{ route('admin-province.laporan.show-regency', $regency->code) }}"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                            Lihat Laporan RTK
                                        </a>
                                    </li>
                                    @if ($regency->latest_rtk)
                                        <li>
                                            <a href="{{ Storage::url($regency->latest_rtk->document_path) }}"
                                                download="{{ $regency->latest_rtk->name }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Download</a>
                                        </li>
                                        <li>
                                            <button type="button" x-data
                                                @click="$dispatch('open-modal', 'open-document-kab-kota-{{ $regency->code }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                Preview Dokumen RTK
                                            </button>

                                            <x-modal name="open-document-kab-kota-{{ $regency->code }}"
                                                title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                                                <h1 class="text-lg font-semibold mb-3">{{ $regency->latest_rtk->name }}</h1>
                                                <div class="border border-gray-300 rounded-md overflow-hidden">
                                                    @if ($regency->latest_rtk->document_path && Storage::disk('public')->exists($regency->latest_rtk->document_path))
                                                        <iframe
                                                            src="{{ Storage::url($regency->latest_rtk->document_path) }}"
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
                                                        @click="$dispatch('close-modal', 'open-document-kab-kota-{{ $regency->code }}')"
                                                        class="inline-flex items-center justify-center px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-100 hover:text-neutral-600 hover:bg-neutral-100"
                                                    >
                                                        Close
                                                    </button>
                                                </x-slot:footer>
                                            </x-modal>
                                        </li>
                                    @endif
                                </x-table.action>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="9" align="center" class="py-12">
                                <p class="text-sm text-slate-500">Tidak ada data provinsi</p>
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
</x-dashboard::layouts.dashboard>