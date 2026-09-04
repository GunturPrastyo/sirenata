<x-dashboard::layouts.dashboard title="Rencana Tenaga Kerja Daerah">
    <div class="p-2 sm:p-6  pt-6 sm:pt-8">

        <div class="mb-6">
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[
            ['label' => 'Daftar Laporan RTKD Provinsi', 'url' => route('admin-pusat.rtkd.index')],
            ['label' => 'Daftar Laporan RTKD Kab/Kota']
        ]" />
        </div>

        <x-dashboard::filter-card 
            title="Daftar Laporan RTKD Kabupaten/Kota" 
            :total="$rtkds->total()"
            :resetUrl="route('admin-pusat.rtkd.kab-kota', $provinceCode)">
            
            <x-slot name="actions">
                <x-button variant="success" :href="route('admin-pusat.rtkd.export-regency-by-province', $provinceCode) .
                    '?' .
                    http_build_query(request()->only(['search', 'status_verification', 'status_document', 'acuan']))">
                    <i class="fas fa-download text-xs mr-2"></i>
                    Ekspor
                </x-button>
            </x-slot>

            <x-slot name="filter_inputs">
                <!-- Status Verifikasi -->
                <div class="w-full sm:w-44 lg:flex-1">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Status Verifikasi</label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="status_verification" class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                <div class="w-full sm:w-44 lg:flex-1">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Status Dokumen</label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="status_document" class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                <div class="w-full sm:w-36 lg:w-32">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Data per Halaman</label>
                    <select name="per_page" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ([10, 20, 50, 100] as $page)
                            <option value="{{ $page }}" {{ request('per_page') == $page ? 'selected' : '' }}>{{ $page }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pencarian -->
                <div class="w-full sm:flex-1 lg:flex-[1.5]">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Pencarian</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama Kab/Kota..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead>
                    <tr class="text-slate-500 uppercase text-xs">
                        <x-table.th class="text-center w-16">No</x-table.th>
                        <x-table.th class="w-48">Instansi (Provinsi)</x-table.th>
                        {{-- Lebar kolom Nama Dokumen diperbesar --}}
                        <x-table.th class="min-w-[280px]">Nama Dokumen</x-table.th>
                        <x-table.th class="w-40">Periode Berlaku</x-table.th>
                        <x-table.th class="w-36 text-center">Status Verifikasi</x-table.th>
                        <x-table.th class="w-48 text-center">Status Berlaku Dokumen</x-table.th>
                        <x-table.th class="w-48">Disetujui Oleh</x-table.th>
                        <x-table.th class="w-40">Tanggal Diverifikasi</x-table.th>
                        <x-table.th class="text-center w-24">Aksi</x-table.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse ($rtkds as $key => $regency)
                        @php
                            $verifColor = 'slate';
                            $docColor = 'slate';

                            if ($regency->latest_rtk) {
                                // Ekstrak raw value dari object Enum jika ada, diubah ke uppercase agar cocok dengan data asli
                                $valVerif = strtoupper((string)($regency->latest_rtk->status_verification->value ?? $regency->latest_rtk->status_verification));
                                $valDoc = strtoupper((string)($regency->latest_rtk->status_document->value ?? $regency->latest_rtk->status_document));

                                // Logika penentuan warna menggunakan properti "color"
                                $verifColor = match($valVerif) {
                                    'APPROVED' => 'success', // Disetujui -> Hijau
                                    'PENDING' => 'warning',  // Menunggu Persetujuan -> Kuning
                                    'REJECTED' => 'danger',  // Ditolak -> Merah
                                    default => 'slate',
                                };

                                $docColor = match($valDoc) {
                                    'VALID' => 'success',    // Berlaku -> Hijau
                                    'EXPIRED' => 'danger',   // Kadaluarsa -> Merah
                                    default => 'slate',
                                };
                            }
                        @endphp

                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td class="text-center text-slate-600 align-top pt-4">
                                {{ $key + $rtkds->firstItem() }}
                            </x-table.td>

                            {{-- Nama Provinsi + Tooltip pending_rtk_count --}}
                            <x-table.td class="align-top pt-4">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-slate-700">{{ $regency->name }}</p>

                                    @if (($regency->pending_rtk_count ?? 0) > 0)
                                        <div class="relative group">
                                            <span
                                                class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-amber-500 rounded-full cursor-pointer">
                                                {{ $regency->pending_rtk_count }}
                                            </span>
                                            {{-- Tooltip --}}
                                            <div
                                                class="absolute left-6 top-0 z-20 hidden group-hover:block w-52 bg-gray-800 text-white text-xs rounded-md px-3 py-2 shadow-lg whitespace-normal">
                                                Ada {{ $regency->pending_rtk_count }} RTK yang menunggu persetujuan
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </x-table.td>

                            {{-- Nama Dokumen (Tanpa label di bawahnya, hanya teks nama dokumen) --}}
                            <x-table.td class="align-top pt-4">
                                @if ($regency->latest_rtk)
                                    <p class="font-semibold text-slate-700">{{ $regency->latest_rtk->name }}</p>
                                @else
                                    <span class="text-slate-400 italic">Belum ada dokumen</span>
                                @endif
                            </x-table.td>

                            {{-- Periode --}}
                            <x-table.td class="align-top pt-4">
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

                            {{-- Status Verifikasi (Dikembalikan ke kolom masing-masing) --}}
                            <x-table.td class="text-center align-top pt-4">
                                @if ($regency->latest_rtk)
                                    <x-badge :color="$verifColor">
                                        {{ $regency->latest_rtk->status_verification_label ?? $regency->latest_rtk->status_verification->label() }}
                                    </x-badge>
                                @else
                                    <x-badge color="slate">
                                        Belum ada verifikasi
                                    </x-badge>
                                @endif
                            </x-table.td>

                            {{-- Status Berlaku Dokumen (Dikembalikan ke kolom masing-masing) --}}
                            <x-table.td class="text-center align-top pt-4">
                                @if ($regency->latest_rtk)
                                    @if(strtoupper((string)($regency->latest_rtk->status_document->value ?? $regency->latest_rtk->status_document)) !== 'NA')
                                        <x-badge :color="$docColor">
                                            {{ $regency->latest_rtk->status_document_label ?? $regency->latest_rtk->status_document->label() }}
                                        </x-badge>
                                    @else
                                        <x-badge color="slate">N/A</x-badge>
                                    @endif
                                @else
                                    <x-badge color="slate">
                                        Belum ada status dokumen
                                    </x-badge>
                                @endif
                            </x-table.td>

                            <x-table.td class="text-slate-600 align-top pt-4">
                                {{ $regency->latest_rtk?->display_name_approver ?? '-' }}
                            </x-table.td>

                            {{-- Tanggal Diverifikasi --}}
                            <x-table.td class="text-slate-600 align-top pt-4">
                                {{ $regency->latest_rtk?->approved_at?->format('d M Y') ?? '-' }}
                            </x-table.td>

                            {{-- Aksi --}}
                            <x-table.td class="text-center align-top pt-4">
                                <x-table.action>
                                    <li>
                                        <a href="{{ route('admin-pusat.rtkd.show-regency', $regency->code) }}"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-sm">
                                            Lihat Laporan RTK
                                        </a>
                                    </li>
                                    @if ($regency->latest_rtk)
                                        <li>
                                            <a href="{{ Storage::url($regency->latest_rtk->document_path) }}"
                                                download="{{ $regency->latest_rtk->name }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-sm">Download</a>
                                        </li>
                                        <li>
                                            <button type="button" x-data
                                                @click="$dispatch('open-modal', 'open-document-kab-kota-{{ $regency->code }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-sm">
                                                Preview Dokumen RTK
                                            </button>

                                            <x-modal name="open-document-kab-kota-{{ $regency->code }}"
                                                title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                                                <h1 class="font-semibold text-base mb-3">{{ $regency->latest_rtk->name }}</h1>
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
                                                        class="inline-flex items-center justify-center px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-100 hover:text-neutral-600 hover:bg-neutral-100">Close</button>
                                                </x-slot:footer>
                                            </x-modal>
                                        </li>
                                    @endif
                                </x-table.action>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="9" class="px-6 py-12 text-center">
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