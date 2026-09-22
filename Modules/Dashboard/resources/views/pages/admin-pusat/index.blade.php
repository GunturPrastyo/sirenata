<x-dashboard::layouts.dashboard title="Dashboard Admin Pusat">
    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto space-y-6 sm:space-y-8 bg-slate-50/50 min-h-screen">

        <!-- ===================================== -->
        <!-- 1. STATS GRID (4 Core Color Palette)  -->
        <!-- ===================================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

            <!-- RTK Disetujui (Navy #13416B) -->
            <div
                class="relative overflow-hidden bg-[#13416B] text-white rounded-md p-5 sm:p-6 shadow-sm flex items-center justify-between transition-all duration-300 hover:shadow-md hover:-translate-y-1 group z-0">
                <div
                    class="absolute -right-6 -bottom-6 text-white opacity-[0.05] group-hover:opacity-[0.1] transition-all duration-500 pointer-events-none transform group-hover:scale-110 z-0">
                    <i class="fas fa-check-double text-[130px]"></i>
                </div>

                <div class="relative z-10">
                    <p class="text-white text-sm font-semibold uppercase tracking-wider mb-1">RTK Disetujui</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                        {{ $rtkStatusDistribution->get('approved', 0) }}</h3>
                    <p class="text-[10px] text-white mt-1">Dokumen terverifikasi</p>
                </div>
                <div
                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-white text-[#13416B] flex items-center justify-center shrink-0 shadow-sm relative z-10 transition-transform duration-300 group-hover:scale-105">
                    <i class="fas fa-check-double text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- RTK Menunggu Verifikasi (Slate Blue #547996) -->
            <div
                class="relative overflow-hidden bg-[#547996] text-white rounded-md p-5 sm:p-6 shadow-sm flex items-center justify-between transition-all duration-300 hover:shadow-md hover:-translate-y-1 group z-0">
                <div
                    class="absolute -right-6 -bottom-6 text-white opacity-[0.05] group-hover:opacity-[0.1] transition-all duration-500 pointer-events-none transform group-hover:scale-110 z-0">
                    <i class="fas fa-hourglass-half text-[130px]"></i>
                </div>

                <div class="relative z-10">
                    <p class="text-white text-sm font-semibold uppercase tracking-wider mb-1">Status Menunggu</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                        {{ $rtkStatusDistribution->get('pending', 0) }}</h3>
                    <p class="text-[10px] text-white mt-1">Butuh peninjauan</p>
                </div>
                <div
                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-white text-[#547996] flex items-center justify-center shrink-0 shadow-sm relative z-10 transition-transform duration-300 group-hover:scale-105">
                    <i class="fas fa-hourglass-half text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- RTK Ditolak (Light Blue #8BB1CC) -->
            <div
                class="relative overflow-hidden bg-[#8BB1CC] text-white rounded-md p-5 sm:p-6 shadow-sm flex items-center justify-between transition-all duration-300 hover:shadow-md hover:-translate-y-1 group z-0">
                <div
                    class="absolute -right-6 -bottom-6 text-white opacity-[0.1] group-hover:opacity-[0.15] transition-all duration-500 pointer-events-none transform group-hover:scale-110 z-0">
                    <i class="fas fa-ban text-[130px]"></i>
                </div>

                <div class="relative z-10">
                    <p class="text-white text-sm font-semibold uppercase tracking-wider mb-1">RTK Ditolak</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                        {{ $rtkStatusDistribution->get('rejected', 0) }}</h3>
                    <p class="text-[10px] text-white mt-1">Dikembalikan ke daerah</p>
                </div>
                <div
                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-white text-[#547996] flex items-center justify-center shrink-0 shadow-sm relative z-10 transition-transform duration-300 group-hover:scale-105">
                    <i class="fas fa-ban text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- RTK Berlaku (Muted Green #79A736) -->
            <div
                class="relative overflow-hidden bg-[#79A736] text-white rounded-md p-5 sm:p-6 shadow-sm flex items-center justify-between transition-all duration-300 hover:shadow-md hover:-translate-y-1 group z-0">
                <div
                    class="absolute -right-6 -bottom-6 text-white opacity-[0.05] group-hover:opacity-[0.1] transition-all duration-500 pointer-events-none transform group-hover:scale-110 z-0">
                    <i class="fas fa-file-contract text-[130px]"></i>
                </div>

                <div class="relative z-10">
                    <p class="text-white text-sm font-semibold uppercase tracking-wider mb-1">RTK Berlaku</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                        {{ $rtkMasaAktifPerProvinsi->sum('total') ?? 0 }}</h3>
                    <p class="text-[10px] text-white mt-1">Dokumen acuan aktif</p>
                </div>
                <div
                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-white text-[#79A736] flex items-center justify-center shrink-0 shadow-sm relative z-10 transition-transform duration-300 group-hover:scale-105">
                    <i class="fas fa-file-contract text-xl sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 2. GRAFIK KOMPARASI RTK & CARD PERSETUJUAN (GRID LAYOUT)  -->
        <!-- ========================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- LEFT: GRAFIK KOMPARASI RTK (8 Columns) -->
            <div
                class="lg:col-span-7 xl:col-span-8 bg-white rounded-md shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div
                    class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Komparasi Masa Berlaku RTK</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Tahun penyusunan dan masa berakhir dokumen per
                            Provinsi</p>
                    </div>

                    <!-- Dropdown Filter Tahun -->
                    <div class="w-full sm:w-auto sm:max-w-xs shrink-0 flex items-center gap-2">
                        <select id="rtkYearFilter" onchange="fetchRtkPusatData(this.value)"
                            class="w-full text-sm border-slate-200 rounded-lg focus:ring-[#13416B] focus:border-[#13416B] text-ellipsis overflow-hidden cursor-pointer bg-slate-50">
                            <option value="all" {{ $selectedRtkYear === 'all' ? 'selected' : '' }}>Semua Tahun
                                (Default)</option>
                            @foreach ($rtkYearsOptions as $y)
                                <option value="{{ $y }}"
                                    {{ (string) $y === (string) $selectedRtkYear ? 'selected' : '' }}>
                                    Mulai {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="p-5 sm:p-6 flex-1">
                    <!-- CHART CONTAINER DENGAN SCROLL VERTICAL -->
                    <div
                        class="max-h-[500px] overflow-y-auto custom-scrollbar pr-2 {{ $rtkMasaAktifPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                        <div id="rtkCombinedChartContainer" class="relative w-full" style="min-height: 400px;">
                            <canvas id="rtkCombinedBarChart"></canvas>
                        </div>
                    </div>

                    <!-- EMPTY STATE -->
                    <div id="rtkCombinedEmptyState"
                        class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4 {{ $rtkMasaAktifPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                        <div
                            class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                            <i class="fas fa-chart-area text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Belum ada data RTK</p>
                        <p class="text-xs text-slate-500 mt-1">Belum ada penyusunan dokumen RTK Provinsi yang tercatat.
                        </p>
                    </div>
                </div>
            </div>

            <!-- RIGHT: CARD MEMBUTUHKAN PERSETUJUAN / ACC (4 Columns) -->
            <div
                class="lg:col-span-5 xl:col-span-4 bg-white rounded-md shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">Perlu Persetujuan</h2>
                        <p class="text-[11px] text-slate-500">RTK & Projek Daerah yang menunggu ACC</p>
                    </div>
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        {{ $totalPendingApprovals }}
                    </span>
                </div>

                <div class="p-4 flex-1 flex flex-col justify-between">
                    <!-- CONTAINER LIST PERSAINGAN DENGAN MAX-HEIGHT & INFINITE SCROLL -->
                    <div id="pendingScrollContainer"
                        class="max-h-[500px] overflow-y-auto custom-scrollbar pr-1 space-y-3">
                        <div id="pendingApprovalList" class="space-y-3">
                            @forelse($initialPendingApprovals as $item)
                                <div
                                    class="p-3.5 bg-slate-50 hover:bg-slate-100/80 rounded-lg border border-slate-200 transition-all duration-200 flex flex-col gap-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border {{ $item['badge_color'] }}">
                                            {{ $item['category'] }}
                                        </span>
                                        <span class="text-[10px] font-medium text-slate-400">
                                            <i class="far fa-clock mr-1"></i>{{ $item['date_formatted'] }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-800 line-clamp-1"
                                            title="{{ $item['title'] }}">{{ $item['title'] }}</h4>
                                        <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $item['subtitle'] }}
                                        </p>
                                    </div>
                                    <div class="flex justify-end pt-1 border-t border-slate-200/60 mt-1">
                                        <a href="{{ $item['url'] }}"
                                            class="inline-flex items-center text-[11px] font-semibold text-[#13416B] hover:text-[#547996] transition-colors">
                                            Tinjau Persetujuan <i class="fas fa-chevron-right ml-1 text-[9px]"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="bg-slate-50 rounded-lg p-8 text-center border border-dashed border-slate-200 my-2">
                                    <div
                                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm text-slate-400">
                                        <i class="fas fa-check-circle text-lg text-emerald-500"></i>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-700">Semua Terproses</p>
                                    <p class="text-[10px] text-slate-500 mt-1">Tidak ada pengajuan RTK atau Projek yang
                                        memerlukan persetujuan.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- SPINNER LOADING PERMINTAAN DATA BARU -->
                        <div id="pendingLoadingSpinner" class="hidden py-3 text-center">
                            <i class="fas fa-spinner fa-spin text-[#13416B] text-lg"></i>
                            <span class="text-[11px] text-slate-500 ml-2">Memuat data berikutnya...</span>
                        </div>

                        <!-- PESAN AKHIR DATA -->
                        <div id="pendingEndMessage"
                            class="{{ !$hasMorePendingApprovals && count($initialPendingApprovals) > 0 ? '' : 'hidden' }} pt-2 text-center border-t border-slate-100">
                            <p class="text-[10px] text-slate-400 font-medium">Semua data persetujuan telah ditampilkan
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 3. DISTRIBUSI E-LEARNING (SPLIT HTML LEADERBOARD)         -->
        <!-- ========================================================= -->
        <div class="bg-white rounded-md shadow-sm border border-slate-200 overflow-hidden">
            <div
                class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Distribusi Pendaftar E-Learning</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Jumlah pengguna terdaftar berdasarkan Provinsi
                        </p>
                    </div>
                </div>

                <div class="w-full sm:w-auto sm:max-w-xs shrink-0">
                    <select id="sdmYearFilter" onchange="fetchSdmPusatData(this.value)"
                        class="w-full text-sm border-slate-200 rounded-lg focus:ring-[#13416B] focus:border-[#13416B] text-ellipsis overflow-hidden cursor-pointer bg-slate-50">
                        @foreach ($sdmYears as $year)
                            <option value="{{ $year }}" {{ $selectedSdmYear == $year ? 'selected' : '' }}>
                                Tahun Registrasi {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="p-5 sm:p-6">
                <!-- LEADERBOARD CONTAINER -->
                <div id="sdmListContainer"
                    class="space-y-6 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar {{ $sdmPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                    <!-- Di-render via JavaScript agar dinamis -->
                </div>

                <!-- EMPTY STATE -->
                <div id="sdmEmptyState"
                    class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4 {{ $sdmPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                    <div
                        class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                        <i class="fas fa-users-slash text-xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Belum Ada Pendaftar</p>
                    <p class="text-xs text-slate-500 mt-1">Belum ada pengguna yang mendaftar pada tahun tersebut.</p>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                function formatMultilineLabel(text) {
                    if (!text) return text;
                    const maxChars = window.innerWidth >= 640 ? 25 : 15;
                    const words = text.split(' ');
                    let lines = [];
                    let currentLine = '';

                    words.forEach(word => {
                        if ((currentLine + word).length > maxChars) {
                            if (currentLine.trim() !== '') lines.push(currentLine.trim());
                            currentLine = word + ' ';
                        } else {
                            currentLine += word + ' ';
                        }
                    });
                    if (currentLine.trim() !== '') lines.push(currentLine.trim());
                    return lines;
                }

                // =========================================================
                // 1. GRAFIK KOMPARASI RTK (HORIZONTAL FLOATING BAR)
                // =========================================================
                @if ($rtkMasaAktifPerProvinsi->count() > 0)
                    const rtkCombinedCtx = document.getElementById('rtkCombinedBarChart').getContext('2d');

                    const rtkLabelsRaw = @json($rtkMasaAktifPerProvinsi->pluck('province_name'));
                    const rtkLabels = rtkLabelsRaw.map(label => formatMultilineLabel(label));

                    const rtkStartData = @json($rtkMasaAktifPerProvinsi->pluck('start_date'));
                    const rtkEndData = @json($rtkMasaAktifPerProvinsi->pluck('end_date'));

                    const floatingData = rtkStartData.map((start, index) => [start, rtkEndData[index]]);

                    const chartColorPalette = ['#13416B', '#547996', '#8BB1CC'];
                    const barColors = floatingData.map((_, i) => chartColorPalette[i % chartColorPalette.length]);

                    window.rtkCombinedChartInstance = new Chart(rtkCombinedCtx, {
                        type: 'bar',
                        data: {
                            labels: rtkLabels,
                            datasets: [{
                                label: 'Periode Aktif',
                                data: floatingData,
                                backgroundColor: barColors,
                                borderRadius: 6,
                                borderSkipped: false,
                                barPercentage: 0.6,
                                categoryPercentage: 0.8
                            }]
                        },
                        options: {
                            indexAxis: 'y',
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
                                    },
                                    callbacks: {
                                        title: function(context) {
                                            return Array.isArray(context[0].label) ? context[0].label.join(
                                                ' ') : context[0].label;
                                        },
                                        label: function(context) {
                                            const startYear = context.raw[0];
                                            const endYear = context.raw[1];
                                            return ` Masa Berlaku: ${startYear} s.d. ${endYear}`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    min: Math.min(...rtkStartData) - 1,
                                    max: Math.max(...rtkEndData) + 1,
                                    grid: {
                                        color: '#f1f5f9'
                                    },
                                    ticks: {
                                        font: {
                                            size: 11
                                        },
                                        stepSize: 1,
                                        callback: function(value) {
                                            return value;
                                        }
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 11,
                                            family: "'Inter', sans-serif"
                                        },
                                        autoSkip: false
                                    },
                                    afterFit: function(scaleInstance) {
                                        scaleInstance.width = window.innerWidth >= 640 ? 160 : 120;
                                    }
                                }
                            }
                        }
                    });

                    const initialRtkHeight = Math.max(400, rtkLabelsRaw.length * 50);
                    document.getElementById('rtkCombinedChartContainer').style.height = initialRtkHeight + 'px';
                @endif

                window.fetchRtkPusatData = function(year) {
                    fetch(`{{ route('admin-pusat.dashboard') }}?rtk_year=${year}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            const rtkData = data.rtkMasaAktifPerProvinsi;
                            const containerScroll = document.querySelector('#rtkCombinedChartContainer')
                                .parentElement;
                            const container = document.getElementById('rtkCombinedChartContainer');
                            const emptyState = document.getElementById('rtkCombinedEmptyState');

                            if (!rtkData || rtkData.length === 0) {
                                containerScroll.classList.add('hidden');
                                emptyState.classList.remove('hidden');
                            } else {
                                containerScroll.classList.remove('hidden');
                                emptyState.classList.add('hidden');

                                const rawLabels = rtkData.map(item => item.province_name);
                                const labels = rawLabels.map(label => formatMultilineLabel(label));
                                const start = rtkData.map(item => item.start_date);
                                const end = rtkData.map(item => item.end_date);

                                const newFloatingData = start.map((s, index) => [s, end[index]]);
                                const chartColorPalette = ['#13416B', '#547996', '#8BB1CC'];
                                const newBarColors = newFloatingData.map((_, i) => chartColorPalette[i %
                                    chartColorPalette.length]);

                                if (window.rtkCombinedChartInstance) {
                                    container.style.height = Math.max(400, rawLabels.length * 50) + 'px';

                                    window.rtkCombinedChartInstance.data.labels = labels;
                                    window.rtkCombinedChartInstance.data.datasets[0].data = newFloatingData;
                                    window.rtkCombinedChartInstance.data.datasets[0].backgroundColor =
                                        newBarColors;

                                    const minYear = Math.min(...start) - 1;
                                    const maxYear = Math.max(...end) + 1;
                                    window.rtkCombinedChartInstance.options.scales.x.min = isFinite(minYear) ?
                                        minYear : 2020;
                                    window.rtkCombinedChartInstance.options.scales.x.max = isFinite(maxYear) ?
                                        maxYear : 2030;

                                    window.rtkCombinedChartInstance.update();
                                }
                            }
                        });
                };

                // =========================================================
                // 2. INFINITE SCROLL PADA CARD PERSETUJUAN
                // =========================================================
                let pendingPage = 1;
                let pendingHasMore = @json($hasMorePendingApprovals);
                let pendingLoading = false;

                const pendingContainer = document.getElementById('pendingScrollContainer');

                if (pendingContainer) {
                    pendingContainer.addEventListener('scroll', function() {
                        if (pendingLoading || !pendingHasMore) return;

                        // Deteksi scroll telah mendekati bagian paling bawah (30px buffer)
                        if (pendingContainer.scrollTop + pendingContainer.clientHeight >= pendingContainer
                            .scrollHeight - 30) {
                            loadMorePendingData();
                        }
                    });
                }

                function loadMorePendingData() {
                    pendingLoading = true;
                    document.getElementById('pendingLoadingSpinner').classList.remove('hidden');

                    const nextPage = pendingPage + 1;
                    fetch(`{{ route('admin-pusat.dashboard') }}?pending_page=${nextPage}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(res => {
                            pendingPage = res.page;
                            pendingHasMore = res.has_more;

                            appendPendingItems(res.data);

                            pendingLoading = false;
                            document.getElementById('pendingLoadingSpinner').classList.add('hidden');

                            if (!pendingHasMore) {
                                document.getElementById('pendingEndMessage').classList.remove('hidden');
                            }
                        })
                        .catch(err => {
                            console.error('Error fetching pending approvals:', err);
                            pendingLoading = false;
                            document.getElementById('pendingLoadingSpinner').classList.add('hidden');
                        });
                }

                function appendPendingItems(items) {
                    const listContainer = document.getElementById('pendingApprovalList');

                    items.forEach(item => {
                        const cardHtml = `
                            <div class="p-3.5 bg-slate-50 hover:bg-slate-100/80 rounded-lg border border-slate-200 transition-all duration-200 flex flex-col gap-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border ${item.badge_color}">
                                        ${item.category}
                                    </span>
                                    <span class="text-[10px] font-medium text-slate-400">
                                        <i class="far fa-clock mr-1"></i>${item.date_formatted}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 line-clamp-1" title="${item.title}">${item.title}</h4>
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5">${item.subtitle}</p>
                                </div>
                                <div class="flex justify-end pt-1 border-t border-slate-200/60 mt-1">
                                    <a href="${item.url}" class="inline-flex items-center text-[11px] font-semibold text-[#13416B] hover:text-[#547996] transition-colors">
                                        Tinjau Persetujuan <i class="fas fa-chevron-right ml-1 text-[9px]"></i>
                                    </a>
                                </div>
                            </div>
                        `;
                        listContainer.insertAdjacentHTML('beforeend', cardHtml);
                    });
                }

                // =========================================================
                // 3. GRAFIK DISTRIBUSI E-LEARNING (SPLIT HTML LEADERBOARD)
                // =========================================================
                function renderSdmLeaderboard(data) {
                    const container = document.getElementById('sdmListContainer');
                    const emptyState = document.getElementById('sdmEmptyState');

                    if (!data || data.length === 0) {
                        container.classList.add('hidden');
                        emptyState.classList.remove('hidden');
                        return;
                    }

                    container.classList.remove('hidden');
                    emptyState.classList.add('hidden');

                    let maxTotal = Math.max(...data.map(item => item.total));
                    if (maxTotal === 0) maxTotal = 1;

                    const sortedData = [...data].sort((a, b) => b.total - a.total);

                    let htmlContent = '';
                    sortedData.forEach((item, index) => {
                        const maleWidth = (item.male / maxTotal) * 100;
                        const femaleWidth = (item.female / maxTotal) * 100;

                        const wilayahName = item.province_name;

                        htmlContent += `
                            <div class="flex items-center gap-4 group">
                                <div class="w-6 text-sm font-bold text-slate-400 text-right shrink-0 group-hover:text-[#13416B] transition-colors">${index + 1}.</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-sm font-semibold text-slate-700 truncate pr-2 group-hover:text-[#13416B] transition-colors">${wilayahName}</span>
                                        <div class="text-right shrink-0">
                                            <span class="text-sm font-extrabold text-[#13416B]">${item.total}</span>
                                            <span class="text-[10px] font-medium text-slate-500 ml-1">Peserta</span>
                                        </div>
                                    </div>
                                    
                                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden flex shadow-inner">
                                        <div class="bg-[#13416B] h-full transition-all duration-700 ease-out border-r border-white/20" style="width: ${maleWidth}%" title="Laki-laki: ${item.male}"></div>
                                        <div class="bg-[#547996] h-full transition-all duration-700 ease-out" style="width: ${femaleWidth}%" title="Perempuan: ${item.female}"></div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center mt-1.5 px-0.5">
                                        <div class="flex gap-3">
                                            <span class="text-[10px] font-medium text-slate-500"><i class="fas fa-male text-[#13416B] mr-1 text-xs"></i>${item.male}</span>
                                            <span class="text-[10px] font-medium text-slate-500"><i class="fas fa-female text-[#547996] mr-1 text-xs"></i>${item.female}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    container.innerHTML = htmlContent;
                }

                const initialSdmData = @json($sdmPerProvinsi);
                renderSdmLeaderboard(initialSdmData);

                window.fetchSdmPusatData = function(year) {
                    fetch(`{{ route('admin-pusat.dashboard') }}?sdm_year=${year}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            renderSdmLeaderboard(data.sdmPerProvinsi);
                        });
                };
            });
        </script>
    @endpush
</x-dashboard::layouts.dashboard>
