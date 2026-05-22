<x-dashboard::layouts.dashboard title="Rencana Tenaga Kerja Daerah">
    <div class="p-2 sm:p-6">
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[
            ['label' => 'Daftar Laporan RTKD']
        ]" />

        <x-dashboard::filter-card 
            title="Daftar Laporan RTKD Provinsi" 
            :total="$rtkds->total()"
            :resetUrl="route('admin-pusat.rtkd.index')">
            
            <x-slot name="actions">
                <x-button :href="route('admin-pusat.rtkd.export-all-province') . '?' . http_build_query(request()->only(['search', 'status_verification', 'status_document', 'acuan']))"
                    variant="success" size="sm" icon="fas fa-download" title="Ekspor Data">
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
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama provinsi..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th class="text-center w-16">No</x-table.th>
                        <x-table.th class="text-left w-48">Instansi (Provinsi)</x-table.th>
                        <x-table.th class="text-left">Nama Dokumen</x-table.th>
                        <x-table.th class="text-left w-40">Periode Berlaku</x-table.th>
                        <x-table.th class="text-left w-36">Status Verifikasi</x-table.th>
                        <x-table.th class="text-left w-48">Status Berlaku Dokumen</x-table.th>
                        <x-table.th class="text-left w-40">Tanggal Diverifikasi</x-table.th>
                        <x-table.th class="text-center w-24">Aksi</x-table.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @forelse ($rtkds as $key => $province)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td align="center" class="text-slate-600">
                                {{ $key + $rtkds->firstItem() }}
                            </x-table.td>

                            {{-- Nama Provinsi + Tooltip pending_rtk_count --}}
                            <x-table.td>
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-slate-700">{{ $province->name }}</p>

                                    @if(($province->pending_rtk_count ?? 0) > 0)
                                        <div class="relative group">
                                            <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-amber-500 rounded-full cursor-pointer">
                                                {{ $province->pending_rtk_count }}
                                            </span>
                                            {{-- Tooltip --}}
                                            <div class="absolute left-6 top-0 z-20 hidden group-hover:block w-52 bg-gray-800 text-white text-xs rounded-md px-3 py-2 shadow-lg whitespace-normal">
                                                Ada {{ $province->pending_rtk_count }} RTK yang menunggu persetujuan
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </x-table.td>

                            {{-- Nama Dokumen --}}
                            <x-table.td>
                                @if ($province->latest_rtk)
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-slate-700">{{ $province->latest_rtk->name }}</p>
                                        {{-- Badge RTK Berlaku --}}
                                        @if($province->latest_rtk->is_berlaku)
                                            <x-badge color="success">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Berlaku
                                            </x-badge>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Belum ada dokumen</span>
                                @endif
                            </x-table.td>

                            {{-- Periode --}}
                            <x-table.td>
                                @if ($province->latest_rtk)
                                    <span class="text-slate-600">
                                        {{ $province->latest_rtk->start_date }}
                                        <span class="mx-1 text-slate-400">–</span>
                                        {{ $province->latest_rtk->end_date }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </x-table.td>

                            {{-- Status Verifikasi --}}
                            <x-table.td align="center">
                                @if ($province->latest_rtk)
                                    <x-badge color="{{ $province->latest_rtk->status_verification === \Modules\RTK\Enums\RTKStatusVerification::APPROVED ? 'success' : ($province->latest_rtk->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING ? 'indigo' : 'red') }}" :text="$province->latest_rtk->status_verification_label" />
                                @else
                                    <x-badge color="slate" text="Belum ada verifikasi" />
                                @endif
                            </x-table.td>

                            {{-- Status Dokumen --}}
                            <x-table.td align="center">
                                @if ($province->latest_rtk)
                                    <x-badge color="{{ $province->latest_rtk->status_document === \Modules\RTK\Enums\StatusDocument::VALID ? 'success' : ($province->latest_rtk->status_document === \Modules\RTK\Enums\StatusDocument::EXPIRED ? 'red' : 'slate') }}" :text="$province->latest_rtk->status_document_label" />
                                @else
                                    <x-badge color="slate" text="Belum ada status dokumen" />
                                @endif
                            </x-table.td>

                            {{-- Tanggal Diverifikasi --}}
                            <x-table.td class="text-slate-600">
                                {{ $province->latest_rtk?->approved_at?->format('d M Y') ?? '-' }}
                            </x-table.td>

                            {{-- Aksi --}}
                            <x-table.td align="center">
                                <x-table.action>
                                    <li>
                                        <a href="{{ route('admin-pusat.rtkd.kab-kota', $province->code) }}"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-sm">
                                            Rekap Kab/Kota
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin-pusat.rtkd.show-province', $province->code) }}"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-sm">
                                            Lihat Laporan RTK
                                        </a>
                                    </li>

                                    @if ($province->latest_rtk)
                                        <li>
                                            <a href="{{ Storage::url($province->latest_rtk->document_path) }}"
                                                download="{{ $province->latest_rtk->name }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-sm">
                                                Download
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button" x-data
                                                @click="$dispatch('open-modal', 'open-document-province-{{ $province->code }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-sm">
                                                Preview Dokumen
                                            </button>
                                        </li>

                                        <x-modal name="open-document-province-{{ $province->code }}" title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                                            <h1 class="font-semibold text-base mb-3">{{ $province->latest_rtk->name }}</h1>
                                            <div class="border border-gray-300 rounded-md overflow-hidden">
                                                @if ($province->latest_rtk->document_path && Storage::disk('public')->exists($province->latest_rtk->document_path))
                                                    <iframe src="{{ Storage::url($province->latest_rtk->document_path) }}" class="w-full min-h-[500px] rounded-md border"></iframe>
                                                @else
                                                    <div class="flex items-center justify-center min-h-[500px] text-gray-400 border rounded-md">
                                                        Tidak ada dokumen tersimpan
                                                    </div>
                                                @endif
                                            </div>
                                            <x-slot:footer>
                                                <button @click="$dispatch('close-modal', 'open-document-province-{{ $province->code }}')"
                                                    class="inline-flex items-center justify-center px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 hover:text-neutral-600 hover:bg-neutral-100">
                                                    Close
                                                </button>
                                            </x-slot:footer>
                                        </x-modal>
                                    @endif
                                </x-table.action>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="8" class="px-6 py-12 text-center">
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