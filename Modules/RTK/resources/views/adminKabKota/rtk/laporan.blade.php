<x-dashboard::layouts.dashboard title="Laporan RTK">
    <div class="p-2 sm:p-6 space-y-6">
        <x-breadcrumb :items="[['label' => 'Laporan RTK']]" />

        {{-- Notifikasi Jika Masa Berlaku Habis --}}
        @if ($rtkKabKotaActive && $rtkKabKotaActive->status_document === \Modules\RTK\Enums\StatusDocument::EXPIRED)
            <div class="rounded-xl bg-red-50 border border-red-100 p-4 sm:p-5 flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation-triangle text-base"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-red-800 text-sm sm:text-base">
                        RTK Telah Melewati Masa Berlaku
                    </h2>
                    <p class="text-xs sm:text-sm text-red-700 mt-1">
                        Periode aktif dokumen RTK ini berakhir pada akhir tahun
                        <strong>{{ $rtkKabKotaActive->end_date }}</strong>. Silakan lakukan penyusunan atau pembaruan
                        dokumen RTK terbaru.
                    </p>
                </div>
            </div>
        @endif

        {{-- Notifikasi Jika Belum Ada RTK --}}
        @if (!$rtkKabKotaActive)
            <div class="rounded-xl bg-blue-50 border border-blue-100 p-4 sm:p-5 flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-[#13416B] flex items-center justify-center shrink-0">
                    <i class="fas fa-info-circle text-base"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-[#13416B] text-sm sm:text-base">
                        Belum Terdapat Dokumen RTK Kabupaten/Kota
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 mb-4">
                        Wilayah Anda belum memiliki dokumen Rencana Tenaga Kerja yang aktif.
                    </p>

                    @if (auth()->user()->hasCompleteScope())
                        <x-button :href="route('admin-kab-kota.rtkd.create')" variant="primary" icon="fas fa-plus" size="sm"
                            class="bg-[#13416B] hover:bg-[#0f3354] font-medium">
                            Buat RTK Baru
                        </x-button>
                    @else
                        <p class="text-xs sm:text-sm text-slate-600 bg-white p-3 rounded-lg border border-slate-200">
                            <i class="fas fa-shield-alt mr-1 text-slate-400"></i> Silakan hubungi Admin Pusat untuk
                            pengaturan wilayah sebelum menyusun RTK.
                        </p>
                    @endif
                </div>
            </div>
        @endif

        {{-- GRID UTAMA --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            {{-- KOLOM KIRI (7 Kolom): Desain Baru Identitas Dokumen (Lebih Kalem) --}}
            <div class="lg:col-span-7 flex flex-col">
                <div
                    class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col h-full shadow-sm">

                    {{-- Header Card (Putih Bersih) --}}
                    <div class="p-6 sm:p-8 border-b border-slate-100 shrink-0">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="px-3 py-1.5 bg-blue-50/50 text-[#13416B] rounded-md text-xs font-medium border border-blue-100/50 flex items-center gap-1.5 inline-flex">
                                <i class="fas fa-map-marker-alt text-blue-400"></i>
                                {{ $rtkKabKotaActive?->regency?->name ?? 'Wilayah Tidak Diketahui' }}
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 leading-snug">
                            {{ $rtkKabKotaActive?->name ?? 'RTK Belum Tersedia' }}
                        </h1>
                    </div>
                   {{-- Body Card: Rincian Data --}}
                    <div class="p-6 sm:p-8 flex-1 flex flex-col justify-between">
                        @if ($rtkKabKotaActive)
                            @php
                                $isValid = $rtkKabKotaActive->status_document === \Modules\RTK\Enums\StatusDocument::VALID;
                                $isExpired = $rtkKabKotaActive->status_document === \Modules\RTK\Enums\StatusDocument::EXPIRED;
                                
                                $startYear = $rtkKabKotaActive->start_date;
                                $endYear = $rtkKabKotaActive->end_date;
                                $spanTahun = ($startYear && $endYear) ? (intval($endYear) - intval($startYear) + 1) : '-';
                            @endphp

                            <div class="space-y-5">
                                {{-- Status Bar (Proporsional: tidak terlalu tipis & tidak mencolok) --}}
                                @if ($isValid)
                                    <div class="flex items-center justify-between p-4 rounded-xl border bg-emerald-50/70 border-emerald-200">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-9 h-9 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                                <i class="fas fa-check text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-emerald-800">Status Dokumen</p>
                                                <p class="text-sm font-semibold text-emerald-700">Berlaku Aktif</p>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($isExpired)
                                    <div class="flex items-center justify-between p-4 rounded-xl border bg-red-50/70 border-red-200">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-9 h-9 rounded-lg bg-red-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                                <i class="fas fa-times text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-red-800">Status Dokumen</p>
                                                <p class="text-sm font-semibold text-red-700">Telah Kadaluarsa</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between p-4 rounded-xl border bg-amber-50/80 border-amber-200">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-9 h-9 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                                                <i class="fas fa-clock text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-amber-900">Status Dokumen</p>
                                                <p class="text-sm font-semibold text-amber-800">Belum Berlaku</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Grid Periode --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="border border-slate-200 bg-white rounded-xl p-4 flex flex-col gap-1.5 hover:border-blue-200 transition-colors">
                                        <div class="flex items-center gap-2 mb-1">
                                            <i class="far fa-calendar-plus text-slate-400"></i>
                                            <p class="text-xs font-medium text-slate-500">Berlaku Mulai</p>
                                        </div>
                                        <p class="text-base font-semibold text-slate-800">1 Jan {{ $startYear ?? '-' }}</p>
                                    </div>

                                    <div class="border border-slate-200 bg-white rounded-xl p-4 flex flex-col gap-1.5 hover:border-blue-200 transition-colors">
                                        <div class="flex items-center gap-2 mb-1">
                                            <i class="far fa-calendar-check text-slate-400"></i>
                                            <p class="text-xs font-medium text-slate-500">Berakhir Pada</p>
                                        </div>
                                        <p class="text-base font-semibold text-slate-800">31 Des {{ $endYear ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Rentang Waktu --}}
                            <div class="mt-6 p-4 bg-slate-50 border border-slate-200/80 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center shrink-0 shadow-sm">
                                        <i class="fas fa-hourglass-half text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-slate-500">Rentang Periode RTK</p>
                                        <p class="text-sm font-semibold text-slate-700">{{ $startYear ?? '-' }} s.d. {{ $endYear ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex items-baseline gap-1">
                                    <span class="text-xl font-bold text-slate-700">{{ $spanTahun }}</span>
                                    <span class="text-xs font-medium text-slate-500">Tahun</span>
                                </div>
                            </div>

                        @else
                            {{-- Tampilan Kosong Jika Belum Ada RTK Acuan --}}
                            <div class="flex-1 flex flex-col items-center justify-center text-slate-400 py-12">
                                <div class="w-14 h-14 bg-slate-50 border border-slate-200 rounded-full flex items-center justify-center mb-3 shadow-sm">
                                    <i class="far fa-folder-open text-xl text-slate-400"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Detail Tidak Tersedia</p>
                                <p class="text-xs text-slate-500 mt-1">Belum ada RTK Acuan yang aktif untuk wilayah ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (5 Kolom): PDF Viewer Dokumen --}}
            <div class="lg:col-span-5 flex flex-col">
                <div class="bg-white rounded-2xl p-6 border border-slate-200 flex flex-col h-full shadow-sm">

                    {{-- Header PDF Preview --}}
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-50 text-[#13416B] flex items-center justify-center shrink-0">
                                <i class="far fa-file-pdf text-sm"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-700">Pratinjau Berkas</h3>
                        </div>

                        @if ($rtkKabKotaActive && $rtkKabKotaActive->document_path)
                            <x-button :href="Storage::url($rtkKabKotaActive->document_path)" download="{{ $rtkKabKotaActive->name }}" variant="primary"
                                size="sm" icon="fas fa-download"
                                class="bg-[#13416B] hover:bg-[#0f3354] font-medium shadow-none">
                                Unduh
                            </x-button>
                        @endif
                    </div>

                    {{-- Area Konten Viewer --}}
                    <div class="flex flex-col flex-1 justify-between">
                        @if (
                            $rtkKabKotaActive &&
                                $rtkKabKotaActive->document_path &&
                                Storage::disk('public')->exists($rtkKabKotaActive->document_path))
                            <div
                                class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50 w-full h-[400px] lg:h-full min-h-[400px] relative">
                                <iframe src="{{ Storage::url($rtkKabKotaActive->document_path) }}#toolbar=0&view=FitH"
                                    class="w-full h-full border-0 absolute top-0 left-0" frameborder="0">
                                </iframe>
                            </div>
                        @else
                            <div
                                class="flex flex-col items-center justify-center bg-slate-50 border border-slate-200 border-dashed rounded-xl text-slate-400 gap-3 w-full h-[400px] lg:h-full min-h-[400px]">
                                <div
                                    class="w-12 h-12 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-300">
                                    <i class="far fa-file-pdf text-xl"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-500">Dokumen PDF belum diunggah</p>
                                <p class="text-xs text-slate-400 text-center px-4">File pratinjau akan muncul di sini
                                    setelah diunggah.</p>
                            </div>
                        @endif

                        {{-- Nama File Footer --}}
                        @if ($rtkKabKotaActive)
                            <div
                                class="mt-4 flex items-center text-xs font-medium text-slate-500 bg-slate-50 border border-slate-100 p-3 rounded-xl shrink-0">
                                <i class="fas fa-paperclip text-slate-400 mr-2 shrink-0"></i>
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
