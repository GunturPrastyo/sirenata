<x-dashboard::layouts.dashboard title="Dashboard Admin Kab/Kota">
    <!-- Wrapper Utama -->
    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto space-y-6 sm:space-y-8 bg-slate-50/50 min-h-screen">

        <!-- ===================================== -->
        <!-- BANNER PERINGATAN (KONDISIONAL)       -->
        <!-- ===================================== -->
        <div class="flex flex-col gap-4">
            <!-- 1. Peringatan Wilayah Belum Ditetapkan -->
            @if (!$user->hasCompleteScope())
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 sm:p-5 shadow-sm flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-amber-800 mb-1">Wilayah Kab/Kota Belum Ditetapkan</h2>
                        <p class="text-sm text-amber-700 leading-relaxed">
                            Akun ini belum memiliki penetapan wilayah Kabupaten/Kota pada sistem. Silakan hubungi Admin
                            Pusat untuk konfigurasi wilayah.
                        </p>
                    </div>
                </div>
            @endif

            <!-- 2. Peringatan Belum Ada RTKD Acuan (is_active) -->
            @if (!$rtkStatusInfo['hasAcuan'])
                <div class="bg-sky-50 border border-sky-200 rounded-lg p-4 sm:p-5 shadow-sm flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg bg-sky-100 text-[#13416B] flex items-center justify-center shrink-0">
                            <i class="fas fa-info-circle text-lg"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-sky-900 mb-1">Belum Ada Dokumen RTKD Acuan</h2>
                            <p class="text-xs sm:text-sm text-sky-800 leading-relaxed">
                                Wilayah Anda belum memiliki dokumen yang ditandai sebagai <strong>RTK Acuan (is_active)</strong>.
                                Sesuai ketentuan, <strong>hanya dokumen acuan yang akan diverifikasi dan disetujui oleh Admin Pusat</strong>.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin-kab-kota.rtkd.index') }}"
                        class="shrink-0 px-3.5 py-2 bg-[#13416B] hover:bg-[#103355] text-white text-xs font-bold rounded-lg transition-colors shadow-sm inline-flex items-center gap-1.5">
                        <i class="fas fa-plus"></i> Tetapkan Acuan
                    </a>
                </div>
            @endif

            <!-- 3. Peringatan Dokumen Acuan Ditolak Pusat (Perlu Revisi) -->
            @if ($rtkStatusInfo['isRejected'])
                <div class="bg-rose-50 border border-rose-200 rounded-lg p-4 sm:p-5 shadow-sm flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-exclamation-circle text-lg"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-rose-800 mb-1">Dokumen RTKD Acuan Ditolak oleh Pusat</h2>
                            <p class="text-xs sm:text-sm text-rose-700 leading-relaxed">
                                Dokumen acuan <strong>{{ $rtkAcuan->name }}</strong> dikembalikan oleh verifikator pusat karena memerlukan perbaikan.
                                Silakan periksa catatan revisi dan perbarui dokumen acuan wilayah Anda.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin-kab-kota.rtkd.edit', $rtkAcuan->id) }}"
                        class="shrink-0 px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm inline-flex items-center gap-1.5">
                        <i class="fas fa-edit"></i> Perbaiki Dokumen
                    </a>
                </div>
            @endif

            <!-- 4. Peringatan Masa Berlaku RTKD Segera Berakhir -->
            @if ($rtkStatusInfo['isExpiringSoon'] && $rtkStatusInfo['isValid'])
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 sm:p-5 shadow-sm flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                            <i class="fas fa-clock text-lg"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-amber-800 mb-1">Masa Berlaku RTKD Segera Berakhir</h2>
                            <p class="text-xs sm:text-sm text-amber-700 leading-relaxed">
                                Dokumen acuan <strong>{{ $rtkAcuan->name }}</strong> menyisakan <strong>{{ $rtkStatusInfo['sisaWaktuTeks'] }}</strong>.
                                Disarankan untuk segera mempersiapkan penyusunan dokumen RTK Daerah periode berikutnya.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin-kab-kota.rtkd.create') }}"
                        class="shrink-0 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm inline-flex items-center gap-1.5">
                        <i class="fas fa-plus"></i> Susun Baru
                    </a>
                </div>
            @endif
        </div>

        @php
            $totalSdmPeriode = array_sum($sdmPerTahun);
            $totalModul = count($courses);
        @endphp

        <!-- ========================================================= -->
        <!-- 1. BLOK PERENCANAAN STRATEGIS (RTK & PROJECT)             -->
        <!-- ========================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- KIRI: INFORMASI RTK DAERAH ACUAN (7 Kolom) -->
            <div class="lg:col-span-7 bg-white rounded-md shadow-sm border border-slate-200 overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Dokumen RTK Daerah (RTKD) Acuan</h2>
                            <p class="text-[11px] text-slate-500">Hanya dokumen berstatus Acuan yang diverifikasi & disetujui</p>
                        </div>
                        <a href="{{ route('admin-kab-kota.rtkd.index') }}"
                            class="text-xs font-bold text-[#13416B] hover:text-[#547996] transition-colors">
                            Daftar RTKD
                        </a>
                    </div>

                    <!-- Isi Dokumen RTKD Acuan -->
                    <div class="p-5 sm:p-6 space-y-5">
                        @if ($rtkAcuan)
                            <!-- Box Ringkasan Dokumen Acuan -->
                            <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-3.5">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#13416B] text-white">
                                                <i class="fas fa-check-circle mr-1"></i> RTK Acuan Aktif
                                            </span>
                                            <span class="text-xs text-slate-400">Periode {{ $rtkAcuan->start_date }} - {{ $rtkAcuan->end_date }}</span>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-800 mt-1">{{ $rtkAcuan->name }}</h3>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded text-[11px] font-semibold {{ $rtkStatusInfo['statusBadgeColor'] }}">
                                            {{ $rtkStatusInfo['statusBadgeText'] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-3 border-t border-slate-200/70 text-xs">
                                    <div>
                                        <span class="text-slate-400 text-[11px] block">Masa Berlaku</span>
                                        <span class="font-bold {{ $rtkStatusInfo['sisaWaktuColor'] }}">{{ $rtkStatusInfo['sisaWaktuTeks'] }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 text-[11px] block">Status Dokumen</span>
                                        <span class="font-semibold text-slate-700">{{ $rtkAcuan->status_document?->label() ?? 'Belum Berlaku' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 text-[11px] block">Pembaruan Terakhir</span>
                                        <span class="font-medium text-slate-600">{{ $rtkAcuan->updated_at ? $rtkAcuan->updated_at->diffForHumans() : '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tahapan Persetujuan Admin Pusat (Workflow Stepper) -->
                            <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-3">
                                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-tasks text-[#13416B]"></i> Alur Verifikasi & Pengesahan 
                                </h4>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
                                    <!-- Tahap 1: Penetapan Acuan -->
                                    <div class="p-3 rounded-md border {{ $rtkAcuan->is_active ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200 bg-slate-50' }}">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold {{ $rtkAcuan->is_active ? 'bg-emerald-600 text-white' : 'bg-slate-300 text-slate-600' }}">1</span>
                                            <span class="font-bold text-slate-800">Status Acuan</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500">
                                            {{ $rtkAcuan->is_active ? 'Masuk antrean admin provinsi' : 'Belum aktif' }}
                                        </p>
                                    </div>

                                    <!-- Tahap 2: Verifikasi Pusat -->
                                    <div class="p-3 rounded-md border 
                                        @if($rtkStatusInfo['isApproved']) border-emerald-200 bg-emerald-50/50
                                        @elseif($rtkStatusInfo['isPending']) border-amber-200 bg-amber-50/50
                                        @elseif($rtkStatusInfo['isRejected']) border-rose-200 bg-rose-50/50
                                        @else border-slate-200 bg-slate-50 @endif">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold
                                                @if($rtkStatusInfo['isApproved']) bg-emerald-600 text-white
                                                @elseif($rtkStatusInfo['isPending']) bg-amber-500 text-white
                                                @elseif($rtkStatusInfo['isRejected']) bg-rose-600 text-white
                                                @else bg-slate-300 text-slate-600 @endif">2</span>
                                            <span class="font-bold text-slate-800">Verifikasi Provinsi</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500">
                                            @if($rtkStatusInfo['isApproved']) Verifikasi Disetujui
                                            @elseif($rtkStatusInfo['isPending']) Menunggu Review
                                            @elseif($rtkStatusInfo['isRejected']) Ditolak (Perlu Revisi)
                                            @else Belum Diperiksa @endif
                                        </p>
                                    </div>

                                    <!-- Tahap 3: Pengesahan Dokumen -->
                                    <div class="p-3 rounded-md border {{ $rtkStatusInfo['isValid'] ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200 bg-slate-50' }}">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold {{ $rtkStatusInfo['isValid'] ? 'bg-emerald-600 text-white' : 'bg-slate-300 text-slate-600' }}">3</span>
                                            <span class="font-bold text-slate-800">Pengesahan</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500">
                                            {{ $rtkStatusInfo['isValid'] ? 'Disahkan & Resmi Berlaku' : 'Menunggu Pengesahan' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Empty State: Belum Ada Acuan -->
                            <div class="flex flex-col items-center justify-center text-center py-10 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-amber-500 mb-3">
                                    <i class="fas fa-file-signature text-xl"></i>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">Belum Ada RTKD Acuan</h4>
                                <p class="text-xs text-slate-500 mt-1 max-w-sm">
                                    Admin Pusat hanya memproses dokumen yang ditandai sebagai <strong>RTK Acuan (is_active)</strong>.
                                    Silakan ajukan dokumen atau pilih dokumen yang telah ada untuk diaktifkan sebagai acuan.
                                </p>
                                <a href="{{ route('admin-kab-kota.rtkd.create') }}"
                                    class="mt-4 px-4 py-2 bg-[#13416B] hover:bg-[#103355] text-white text-xs font-bold rounded-lg transition-colors inline-flex items-center gap-1.5 shadow-sm">
                                    <i class="fas fa-plus"></i> Ajukan RTKD Acuan Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="px-5 sm:px-6 py-3.5 bg-slate-50/80 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                    <span class="text-xs text-slate-500">
                        <i class="fas fa-shield-alt text-[#13416B] mr-1"></i> Hanya dokumen acuan aktif yang disetujui admin provinsi
                    </span>
                    <div class="flex items-center gap-2">
                     
                        <a href="{{ route('admin-kab-kota.rtkd.index') }}"
                            class="px-3 py-1.5 rounded-lg bg-[#13416B] hover:bg-[#103355] text-white text-xs font-semibold transition-colors inline-flex items-center gap-1 shadow-sm">
                            <i class="fas fa-list"></i> Kelola Dokumen
                        </a>
                    </div>
                </div>
            </div>

            <!-- KANAN: MONITORING PROYEK DAERAH (5 Kolom) -->
            <div class="lg:col-span-5 bg-white rounded-md shadow-sm border border-slate-200 overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Proyek Daerah</h2>
                            <p class="text-[11px] text-slate-500">Pelaksanaan kegiatan & kesiapan tim</p>
                        </div>
                        <a href="{{ route('admin-kab-kota.project.index') }}"
                            class="text-xs font-bold text-[#13416B] hover:text-[#547996] transition-colors">
                            Semua Proyek
                        </a>
                    </div>

                    <!-- Isi Ringkasan Proyek -->
                    <div class="p-5 sm:p-6 space-y-5">
                        @if ($totalProjects > 0)
                            <!-- Circular Progress Visual -->
                            <div class="flex items-center gap-5 p-3.5 bg-slate-50/80 rounded-lg border border-slate-200/80">
                                <div class="relative w-20 h-20 shrink-0 flex items-center justify-center">
                                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="40" stroke="#e2e8f0" stroke-width="10" fill="none" />
                                        <circle cx="50" cy="50" r="40" stroke="#f59e0b" stroke-width="10"
                                            fill="none" stroke-linecap="round"
                                            stroke-dasharray="{{ round(($onProgressProjects / $totalProjects) * 251.2) }} 251.2" />
                                    </svg>
                                    <div class="absolute text-center">
                                        <span class="text-base font-extrabold text-slate-800">{{ round(($onProgressProjects / $totalProjects) * 100) }}%</span>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Status Pengerjaan</h4>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-2xl font-black text-amber-500">{{ $onProgressProjects }}</span>
                                        <span class="text-xs text-slate-500 font-medium">berjalan dari <strong>{{ $totalProjects }}</strong> total</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1">Selesai: <strong>{{ $completedProjects }}</strong> proyek</p>
                                </div>
                            </div>

                            <!-- Daftar Proyek Terkini -->
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Proyek Terbaru</h4>
                                @foreach ($recentProjects as $p)
                                    <div class="p-2.5 rounded-lg border border-slate-100 bg-white hover:bg-slate-50 transition-colors">
                                        <div class="flex justify-between items-start gap-2 mb-1.5">
                                            <a href="{{ route('admin-kab-kota.project.show', $p->id) }}"
                                                class="text-xs font-bold text-slate-800 hover:text-[#13416B] truncate line-clamp-1"
                                                title="{{ $p->name }}">
                                                {{ $p->name }}
                                            </a>
                                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $p->progress >= 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} shrink-0">
                                                {{ $p->progress }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mb-1">
                                            <div class="bg-[#13416B] h-full rounded-full transition-all duration-500" style="width: {{ $p->progress }}%"></div>
                                        </div>
                                        <div class="flex items-center justify-between text-[10px] text-slate-400">
                                            <span><i class="far fa-user mr-1"></i>{{ $p->leader?->name ?? 'Tim Daerah' }}</span>
                                            @if ($p->prerequisiteCourse)
                                                <span class="text-[#547996] font-medium" title="Prasyarat Kursus LMS: {{ $p->prerequisiteCourse->name }}">
                                                    <i class="fas fa-graduation-cap mr-0.5"></i> Ada Prasyarat
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-10 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-slate-400 mb-3">
                                    <i class="fas fa-clipboard-list text-xl"></i>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">Belum Ada Proyek Daerah</h4>
                                <p class="text-xs text-slate-400 mt-1 max-w-xs">Wilayah ini belum memiliki proyek atau kegiatan perencanaan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="px-5 sm:px-6 py-3.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end">
                    <a href="{{ route('admin-kab-kota.project.create') }}"
                        class="px-3.5 py-1.5 rounded-lg bg-[#13416B] hover:bg-[#103355] text-white text-xs font-semibold transition-colors inline-flex items-center gap-1 shadow-sm">
                        <i class="fas fa-plus"></i> Tambah Proyek Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 2. BLOK KAPASITAS SDM & E-LEARNING (LMS ANALYTICS)        -->
        <!-- ========================================================= -->

        <!-- GRAFIK TREN PARTISIPASI SDM -->
        <div class="bg-white rounded-md shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Tren Pendaftaran SDM E-Learning</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">Statistik pertumbuhan peserta dalam blok 5 tahun terakhir</p>
                </div>

                <div class="w-full sm:w-auto sm:max-w-xs shrink-0 flex items-center gap-2">
                    <select id="yearFilter" onchange="filterDataYear(this.value)"
                        class="w-full text-sm border-slate-200 rounded-lg focus:ring-[#13416B] focus:border-[#13416B] cursor-pointer bg-slate-50">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ (string) $y === (string) $selectedYear ? 'selected' : '' }}>
                                Tahun Berakhir {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="p-5 sm:p-6">
                <div id="sdmTrendChartContainer"
                    class="relative h-64 sm:h-72 w-full {{ $totalSdmPeriode > 0 ? '' : 'hidden' }}">
                    <canvas id="sdmTrendBarChart"></canvas>
                </div>

                <div id="sdmTrendEmptyState"
                    class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4 {{ $totalSdmPeriode > 0 ? 'hidden' : '' }}">
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                        <i class="fas fa-chart-area text-xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Belum ada data pendaftar</p>
                    <p class="text-xs text-slate-500 mt-1">Tidak ada catatan SDM pada periode 5 tahun ini.</p>
                </div>
            </div>
        </div>

        <!-- DISTRIBUSI MODUL & GENDER -->
        <div class="bg-white rounded-md shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Modul Terpopuler & Demografi SDM</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">Peringkat kursus yang diikuti dan sebaran gender</p>
                </div>
            </div>

            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Leaderboard Modul (2 Kolom Kiri) -->
                <div class="lg:col-span-2 border-b lg:border-b-0 lg:border-r border-slate-100 pb-6 lg:pb-0 lg:pr-8">
                    <h4 class="text-xs font-bold text-slate-700 mb-5 uppercase tracking-wider">
                        <i class="fas fa-list-ol text-slate-400 mr-1.5"></i> Peringkat Modul Pelatihan Diambil
                    </h4>

                    <div id="courseListContainer"
                        class="space-y-4 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar {{ count($courses) > 0 ? '' : 'hidden' }}">
                        <!-- Dirender via Javascript -->
                    </div>

                    <div id="courseEmptyState"
                        class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4 {{ count($courses) > 0 ? 'hidden' : '' }}">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Belum Ada Modul Diambil</p>
                        <p class="text-xs text-slate-400 mt-1">Belum ada pengguna di wilayah ini yang mendaftar pada modul pelatihan.</p>
                    </div>
                </div>

                <!-- Pie Chart Gender (1 Kolom Kanan) - WARNA KUNING & BIRU -->
                <div class="lg:col-span-1 flex flex-col justify-center">
                    <h4 class="text-xs font-bold text-center text-slate-700 mb-4 uppercase tracking-wider">
                        Distribusi Gender SDM
                    </h4>
                    @if ($genderMale == 0 && $genderFemale == 0)
                        <div class="h-48 flex items-center justify-center text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                            <span class="text-xs font-medium">Data gender kosong</span>
                        </div>
                    @else
                        <div class="relative h-56 w-full flex items-center justify-center">
                            <canvas id="genderPieChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // 4 Kombinasi Palet Warna Serasi
                const colorPalette = ['#13416B', '#547996', '#8BB1CC', '#79A736'];

                // Reload halaman dengan parameter year
                window.filterDataYear = function(year) {
                    window.location.href = `{{ route('admin-kab-kota.dashboard') }}?year=${year}`;
                };

                // 1. GRAFIK TREN SDM (BAR CHART DENGAN 4 WARNA BERGILIR)
                @if ($totalSdmPeriode > 0)
                    const sdmTrendCtx = document.getElementById('sdmTrendBarChart').getContext('2d');
                    const sdmTrendRaw = @json($sdmPerTahun);
                    const sdmLabels = Object.keys(sdmTrendRaw);
                    const sdmData = Object.values(sdmTrendRaw);

                    // Mapping warna selang-seling dari 4 kombinasi warna
                    const barBgColors = sdmLabels.map((_, idx) => colorPalette[idx % colorPalette.length]);
                    const barBorderColors = sdmLabels.map((_, idx) => colorPalette[idx % colorPalette.length]);

                    new Chart(sdmTrendCtx, {
                        type: 'bar',
                        data: {
                            labels: sdmLabels,
                            datasets: [{
                                label: 'Jumlah Pendaftar',
                                data: sdmData,
                                backgroundColor: barBgColors,
                                borderColor: barBorderColors,
                                borderWidth: 1,
                                borderRadius: 6,
                                barPercentage: 0.5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(19, 65, 107, 0.95)',
                                    padding: 12,
                                    cornerRadius: 6,
                                    titleFont: {
                                        size: 13,
                                        weight: 'bold'
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#f1f5f9'
                                    },
                                    ticks: {
                                        font: {
                                            size: 11
                                        },
                                        stepSize: 1
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 12,
                                            weight: 'bold'
                                        }
                                    }
                                }
                            }
                        }
                    });
                @endif

                // 2. LEADERBOARD MODUL (BAR SELANG-SELING 4 WARNA)
                const coursesRaw = @json($courses);

                function renderCourseLeaderboard(dataObj) {
                    const container = document.getElementById('courseListContainer');
                    const emptyState = document.getElementById('courseEmptyState');

                    if (!container) return;

                    const keys = Object.keys(dataObj);
                    if (keys.length === 0) {
                        container.classList.add('hidden');
                        if (emptyState) emptyState.classList.remove('hidden');
                        return;
                    }

                    container.classList.remove('hidden');
                    if (emptyState) emptyState.classList.add('hidden');

                    const coursesArray = keys.map(k => ({
                        name: k,
                        total: dataObj[k]
                    }));
                    coursesArray.sort((a, b) => b.total - a.total);

                    let maxTotal = coursesArray[0].total || 1;
                    let htmlContent = '';

                    coursesArray.forEach((item, index) => {
                        const percentage = (item.total / maxTotal) * 100;
                        const currentColor = colorPalette[index % colorPalette.length];

                        htmlContent += `
                            <div class="flex items-center gap-3.5 group">
                                <div class="w-6 text-sm font-bold text-slate-400 text-right shrink-0">${index + 1}.</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-sm font-semibold text-slate-700 truncate pr-2">${item.name}</span>
                                        <span class="text-sm font-extrabold shrink-0" style="color: ${currentColor}">
                                            ${item.total} <span class="text-[10px] font-medium text-slate-500">Peserta</span>
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-700 ease-out" 
                                             style="width: ${percentage}%; background-color: ${currentColor};"></div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = htmlContent;
                }
                renderCourseLeaderboard(coursesRaw);

                // 3. PIE CHART GENDER (Warna KUNING dan BIRU)
                if (document.getElementById('genderPieChart')) {
                    const genderCtx = document.getElementById('genderPieChart').getContext('2d');
                    new Chart(genderCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Laki-laki', 'Perempuan'],
                            datasets: [{
                                data: [{{ $genderMale }}, {{ $genderFemale }}],
                                // MALE: Biru #13416B | FEMALE: Kuning (Amber-500 #f59e0b)
                                backgroundColor: ['rgba(19, 65, 107, 0.85)',
                                    'rgba(245, 158, 11, 0.85)'
                                ],
                                borderColor: ['#ffffff', '#ffffff'],
                                borderWidth: 2,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 10,
                                        font: {
                                            size: 11
                                        },
                                        padding: 15
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(19, 65, 107, 0.95)',
                                    padding: 10,
                                    cornerRadius: 6,
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = total > 0 ? ((context.parsed / total) * 100)
                                                .toFixed(1) : 0;
                                            return ` ${context.label}: ${context.parsed} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-dashboard::layouts.dashboard>