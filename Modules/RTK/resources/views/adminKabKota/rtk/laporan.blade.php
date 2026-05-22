<x-dashboard::layouts.dashboard title="Laporan RTK">
    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Laporan RTK']]" />

        @if ($rtkKabKotaActive && $rtkKabKotaActive->status_document === \Modules\RTK\Enums\StatusDocument::EXPIRED)
            <div class="mb-5 sm:mb-6 rounded-xl bg-amber-50/80 border border-amber-200 shadow-sm p-4 sm:p-5">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z" />
                    </svg>

                    <div>
                        <h1 class="font-bold text-amber-900 text-sm sm:text-base">
                            RTK telah melewati masa berlaku
                        </h1>
                        <p class="text-xs sm:text-sm text-amber-800 mt-1">
                            Periode RTK berakhir pada tahun
                            <strong>{{ $rtkKabKotaActive->end_date }}</strong>.
                            Silakan segera menyusun RTK terbaru.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (!$rtkKabKotaActive)
            <div class="mb-5 sm:mb-6 rounded-xl bg-sky-50/80 border border-sky-200 shadow-sm p-4 sm:p-5">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-sky-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>

                    <div>
                        <h1 class="font-bold text-sky-900 text-sm sm:text-base">
                            Belum terdapat RTK Kabupaten/Kota
                        </h1>
                        <p class="text-xs sm:text-sm text-sky-800 mt-1 mb-4">
                            Saat ini belum ada RTK yang berstatus berlaku.
                        </p>

                        @if (auth()->user()->hasCompleteScope())
                            <x-button :href="route('admin-kab-kota.rtkd.create')" variant="primary" icon="fas fa-plus" size="sm">
                                Buat RTK Baru
                            </x-button>
                        @else
                            <p class="text-xs sm:text-sm text-sky-700 bg-sky-100/50 p-2.5 rounded-lg border border-sky-200/50">
                                <i class="fas fa-info-circle mr-1 text-sky-600"></i> Silakan hubungi Admin Pusat untuk pengaturan wilayah sebelum membuat RTK baru.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
            <!-- Left Column (6/12) -->
            <div class="space-y-5 sm:space-y-6">
                <!-- RTK Status Card -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50/50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-800 mb-1 leading-snug">
                            {{ $rtkKabKotaActive?->name ?? 'RTK Belum Tersedia' }}
                        </h2>

                        @if ($rtkKabKotaActive?->regency?->name)
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mb-5 flex items-center gap-1.5">
                                <i class="fas fa-map-marker-alt text-slate-400"></i>
                                {{ $rtkKabKotaActive->regency->name }}
                            </p>
                        @else
                            <div class="mb-4"></div>
                        @endif

                        <x-rtk::status-card
                            :rtk="$rtkKabKotaActive"
                            edit-route="admin-kab-kota.rtkd.edit"
                            create-route="admin-kab-kota.rtkd.create" />
                    </div>
                </div>

                <!-- Masa Berlaku RTK -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="far fa-calendar-alt text-indigo-500"></i>
                        Masa Berlaku RTK
                    </h3>

                    <!-- Date Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div class="p-4 bg-indigo-50/50 rounded-xl border border-indigo-100/60 flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-500 text-white rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                                <i class="far fa-calendar-plus text-base"></i>
                            </div>
                            <div>
                                <p class="text-[10px] sm:text-xs font-semibold text-indigo-600 tracking-wider uppercase">Tanggal Mulai</p>
                                <p class="text-sm sm:text-base font-bold text-slate-800 leading-tight">
                                    1 Jan {{ $rtkKabKotaActive?->start_date ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="p-4 bg-emerald-50/50 rounded-xl border border-emerald-100/60 flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-500 text-white rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                                <i class="far fa-calendar-check text-base"></i>
                            </div>
                            <div>
                                <p class="text-[10px] sm:text-xs font-semibold text-emerald-600 tracking-wider uppercase">Tanggal Berakhir</p>
                                <p class="text-sm sm:text-base font-bold text-slate-800 leading-tight">
                                    31 Des {{ $rtkKabKotaActive?->end_date ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Period Summary -->
                    <div class="p-4.5 bg-slate-50 border border-slate-100 rounded-xl relative overflow-hidden group">
                        <div class="flex items-center justify-between relative z-10">
                            <div>
                                <p class="text-[10px] sm:text-xs font-semibold text-slate-500 tracking-wider uppercase mb-1">Periode Berlaku</p>
                                <p class="text-lg sm:text-xl font-extrabold text-slate-800">
                                    @if ($rtkKabKotaActive)
                                        {{ $rtkKabKotaActive->start_date }} - {{ $rtkKabKotaActive->end_date }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 border border-indigo-100 rounded-lg">
                                    <span class="text-xs sm:text-sm font-extrabold text-indigo-600">5</span>
                                    <span class="text-[10px] sm:text-xs font-semibold text-indigo-500 uppercase tracking-wide">Tahun</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column (6/12) - PDF Viewer -->
            <div class="space-y-5 sm:space-y-6">
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-100 sticky top-20 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="far fa-file-pdf text-rose-500"></i>
                            Dokumen RTK
                        </h3>
                        @if ($rtkKabKotaActive)
                            <x-button :href="Storage::url($rtkKabKotaActive->document_path)"
                                download="{{ $rtkKabKotaActive->name }}"
                                variant="primary"
                                size="sm"
                                icon="fas fa-download">
                                Unduh
                            </x-button>
                        @endif
                    </div>
                    
                    @if ($rtkKabKotaActive)
                        <div class="border border-slate-200/80 rounded-xl overflow-hidden bg-slate-50" style="height: 400px;">
                            @if ($rtkKabKotaActive->document_path && Storage::disk('public')->exists($rtkKabKotaActive->document_path))
                                <iframe
                                    src="{{ Storage::url($rtkKabKotaActive->document_path) }}"
                                    class="w-full h-full"
                                    frameborder="0">
                                </iframe>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-slate-400 gap-3">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm font-medium">Dokumen belum diunggah</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center bg-slate-50 border border-slate-200/80 border-dashed rounded-xl text-slate-400 gap-3" style="height: 400px;">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm font-medium">Belum ada RTK yang diunggah</p>
                        </div>
                    @endif

                    @if ($rtkKabKotaActive)
                        <div class="mt-4 flex items-center text-xs font-semibold text-slate-500 bg-slate-50 border border-slate-100 p-2.5 rounded-lg">
                            <svg class="w-4 h-4 mr-2 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">
                                RTK_{{ $rtkKabKotaActive->regency?->name ?? 'Wilayah' }}_{{ $rtkKabKotaActive->start_date }}-{{ $rtkKabKotaActive->end_date }}.pdf
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-dashboard::layouts.dashboard>
