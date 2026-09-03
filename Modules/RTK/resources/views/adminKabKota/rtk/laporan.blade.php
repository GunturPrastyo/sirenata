<x-dashboard::layouts.dashboard title="Laporan RTK">
    <div class="p-2 sm:p-6 space-y-6">
        <x-breadcrumb :items="[['label' => 'Laporan RTK']]" />

        {{-- Notifikasi Jika Masa Berlaku Habis --}}
        @if ($rtkKabKotaActive && $rtkKabKotaActive->status_document === \Modules\RTK\Enums\StatusDocument::EXPIRED)
            <div class="rounded-xl bg-amber-50/90 border border-amber-200 shadow-sm p-4 sm:p-5 flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
                <div>
                    <h2 class="font-bold text-amber-900 text-sm sm:text-base">
                        RTK Telah Melewati Masa Berlaku
                    </h2>
                    <p class="text-xs sm:text-sm text-amber-800 mt-1">
                        Periode aktif dokumen RTK ini berakhir pada akhir tahun <strong>{{ $rtkKabKotaActive->end_date }}</strong>. Silakan lakukan penyusunan atau pembaruan dokumen RTK terbaru.
                    </p>
                </div>
            </div>
        @endif

        {{-- Notifikasi Jika Belum Ada RTK --}}
        @if (!$rtkKabKotaActive)
            <div class="rounded-xl bg-blue-50/90 border border-blue-200 shadow-sm p-4 sm:p-5 flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#13416B] flex items-center justify-center shrink-0">
                    <i class="fas fa-info-circle text-lg"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#13416B] text-sm sm:text-base">
                        Belum Terdapat Dokumen RTK Kabupaten/Kota
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 mb-4">
                        Wilayah Anda belum memiliki dokumen Rencana Tenaga Kerja yang aktif.
                    </p>

                    @if (auth()->user()->hasCompleteScope())
                        <x-button :href="route('admin-kab-kota.rtkd.create')" variant="primary" icon="fas fa-plus" size="sm" class="bg-[#13416B] hover:bg-[#0f3354]">
                            Buat RTK Baru
                        </x-button>
                    @else
                        <p class="text-xs sm:text-sm text-slate-600 bg-white p-3 rounded-lg border border-blue-100 shadow-sm">
                            <i class="fas fa-shield-alt mr-1 text-[#13416B]"></i> Silakan hubungi Admin Pusat untuk pengaturan wilayah sebelum menyusun RTK.
                        </p>
                    @endif
                </div>
            </div>
        @endif

        {{-- GRID UTAMA (Seimbang tingginya) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            
            {{-- KOLOM KIRI (7 Kolom): Informasi & Masa Berlaku RTK --}}
            <div class="lg:col-span-7 flex flex-col gap-6">
                
                {{-- Card 1: Header & Status Dokumen Custom --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 relative overflow-hidden flex flex-col justify-between flex-1">
                    
                    <div class="relative z-10 mb-6">
                      
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 leading-snug mb-2">
                            {{ $rtkKabKotaActive?->name ?? 'RTK Belum Tersedia' }}
                        </h1>

                        @if ($rtkKabKotaActive?->regency?->name)
                            <p class="text-sm text-slate-500 font-medium flex items-center gap-1.5">
                                <i class="fas fa-map-marker-alt text-[#13416B]"></i>
                                Wilayah {{ $rtkKabKotaActive->regency->name }}
                            </p>
                        @endif
                    </div>

                    {{-- Status Card yang sudah disesuaikan tanggal berakhirnya (31 Des [Tahun End Date]) --}}
                    <div class="relative z-10 pt-4 border-t border-slate-100">
                        @if ($rtkKabKotaActive)
                            @php
                                $isExpired = $rtkKabKotaActive->status_document === \Modules\RTK\Enums\StatusDocument::EXPIRED;
                            @endphp
                            <div class="p-4 rounded-xl border {{ $isExpired ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }} flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg {{ $isExpired ? 'bg-amber-500 text-white' : 'bg-emerald-600 text-white' }} flex items-center justify-center shrink-0 shadow-sm">
                                        <i class="fas {{ $isExpired ? 'fa-exclamation-circle' : 'fa-check-circle' }} text-base"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider {{ $isExpired ? 'text-amber-900' : 'text-emerald-900' }}">
                                            {{ $isExpired ? 'RTK KADALUARSA' : 'RTK BERLAKU' }}
                                        </p>
                                        <p class="text-xs font-medium {{ $isExpired ? 'text-amber-700' : 'text-emerald-700' }}">
                                            Berlaku hingga <strong>31 Des {{ $rtkKabKotaActive->end_date }}</strong>
                                        </p>
                                    </div>
                                </div>
                                
                               
                            </div>
                        @else
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 text-center text-sm">
                                Belum ada data status dokumen RTK.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Card 2: Rincian Masa Berlaku --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col justify-between">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#13416B] text-white flex items-center justify-center shrink-0">
                            <i class="far fa-calendar-alt text-sm"></i>
                        </div>
                        Rincian Periode & Masa Berlaku
                    </h3>

                    @php
                        $startYear = $rtkKabKotaActive?->start_date;
                        $endYear = $rtkKabKotaActive?->end_date;
                        $spanTahun = ($startYear && $endYear) ? (intval($endYear) - intval($startYear) + 1) : '-';
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        {{-- Tanggal Mulai --}}
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-3.5">
                            <div class="w-11 h-11 bg-[#13416B] text-white rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <i class="far fa-calendar-plus text-base"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Tahun Mulai</p>
                                <p class="text-base font-extrabold text-slate-800">
                                    1 Jan {{ $startYear ?? '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- Tanggal Berakhir --}}
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex items-center gap-3.5">
                            <div class="w-11 h-11 bg-[#13416B] text-white rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <i class="far fa-calendar-check text-base"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-400 tracking-wider uppercase">Tahun Berakhir</p>
                                <p class="text-base font-extrabold text-slate-800">
                                    31 Des {{ $endYear ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan Total Span Tahun --}}
                    <div class="p-4 bg-[#13416B]/5 border border-[#13416B]/10 rounded-xl flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-0.5">Rentang Periode Dokumen</p>
                            <p class="text-lg font-extrabold text-[#13416B]">
                                @if ($startYear && $endYear)
                                    {{ $startYear }} s.d. {{ $endYear }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-[#13416B] text-white rounded-lg shadow-sm">
                            <span class="text-base font-extrabold">{{ $spanTahun }}</span>
                            <span class="text-xs font-bold uppercase tracking-wide">Tahun</span>
                        </div>
                    </div>
                </div>

            </div>

         {{-- KOLOM KANAN (5 Kolom): PDF Viewer Dokumen --}}
            <div class="lg:col-span-5 flex flex-col">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col h-full">
                    
                    {{-- Header PDF Preview --}}
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-lg bg-[#13416B] text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i class="far fa-file-pdf text-sm"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-800">Pratinjau Berkas</h3>
                        </div>

                        @if ($rtkKabKotaActive && $rtkKabKotaActive->document_path)
                            <x-button :href="Storage::url($rtkKabKotaActive->document_path)"
                                download="{{ $rtkKabKotaActive->name }}"
                                variant="primary"
                                size="sm"
                                icon="fas fa-download"
                                class="bg-[#13416B] hover:bg-[#0f3354]">
                                Unduh
                            </x-button>
                        @endif
                    </div>
                    
                    {{-- Area Konten Viewer (Tinggi dipatok pasti misal 480px agar tidak memicu double scroll flex container) --}}
                    <div class="flex flex-col flex-1 justify-between">
                        @if ($rtkKabKotaActive && $rtkKabKotaActive->document_path && Storage::disk('public')->exists($rtkKabKotaActive->document_path))
                            <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-100 w-full h-[480px] relative">
                                <iframe
                                    src="{{ Storage::url($rtkKabKotaActive->document_path) }}#toolbar=0&view=FitH"
                                    class="w-full h-full border-0 block"
                                    frameborder="0">
                                </iframe>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center bg-slate-50 border border-slate-200 border-dashed rounded-xl text-slate-400 gap-3 w-full h-[480px]">
                                <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center shadow-sm text-slate-300">
                                    <i class="fas fa-file-excel text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Dokumen PDF belum diunggah</p>
                                <p class="text-xs text-slate-400">File pratinjau akan muncul di sini setelah diunggah.</p>
                            </div>
                        @endif

                        {{-- Nama File Footer --}}
                        @if ($rtkKabKotaActive)
                            <div class="mt-4 flex items-center text-xs font-medium text-slate-500 bg-slate-50 border border-slate-200 p-3 rounded-xl shrink-0">
                                <i class="fas fa-paperclip text-[#13416B] mr-2 shrink-0"></i>
                                <span class="truncate">
                                    RTK_{{ $rtkKabKotaActive->regency?->name ?? 'Wilayah' }}_{{ $rtkKabKotaActive->start_date }}-{{ $rtkKabKotaActive->end_date }}.pdf
                                </span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-dashboard::layouts.dashboard>