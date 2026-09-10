<x-dashboard::layouts.dashboard title="Dashboard Admin Pusat">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&display=swap');
        .font-oswald { font-family: 'Oswald', sans-serif; }
    </style>

    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto space-y-6 sm:space-y-8 bg-slate-50/50 min-h-screen">
        
        <!-- ===================================== -->
        <!-- 1. STATS GRID (Full Sirenata Theme)   -->
        <!-- ===================================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            <!-- RTK Disetujui -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider mb-1 font-oswald">RTK Disetujui</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $rtkStatusDistribution->get('approved', 0) }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Dokumen terverifikasi</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#13416B] text-white flex items-center justify-center shrink-0 shadow-sm">
                    <i class="fas fa-check-double text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- RTK Menunggu Verifikasi -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider mb-1 font-oswald">Status Menunggu</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $rtkStatusDistribution->get('pending', 0) }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Butuh peninjauan</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#13416B] text-white flex items-center justify-center shrink-0 shadow-sm">
                    <i class="fas fa-hourglass-half text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- RTK Ditolak -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider mb-1 font-oswald">RTK Ditolak</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $rtkStatusDistribution->get('rejected', 0) }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Dikembalikan ke daerah</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#184A78] text-white flex items-center justify-center shrink-0 shadow-sm border border-[#0f3354]">
                    <i class="fas fa-ban text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- RTK Berlaku -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-sm font-semibold uppercase tracking-wider mb-1 font-oswald">RTK Berlaku</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $rtkMasaAktifPerProvinsi->sum('total') ?? 0 }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Dokumen acuan aktif</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#0f3354] text-white flex items-center justify-center shrink-0 shadow-sm border border-slate-800">
                    <i class="fas fa-file-contract text-xl sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 2. GRAFIK KOMPARASI RTK HORIZONTAL                        -->
        <!-- ========================================================= -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center bg-[#13416B] text-white rounded-xl shrink-0 shadow-sm border border-[#0f3354]">
                        <i class="fas fa-chart-bar text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 font-oswald ">Komparasi Masa Berlaku RTK</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Tahun penyusunan dan masa berakhir dokumen per Provinsi</p>
                    </div>
                </div>

                <!-- Dropdown Filter Tahun -->
                <div class="w-full sm:w-auto sm:max-w-xs shrink-0 flex items-center gap-2">
                    <select id="rtkYearFilter" onchange="fetchRtkPusatData(this.value)" class="w-full text-sm border-slate-200 rounded-lg focus:ring-[#13416B] focus:border-[#13416B] text-ellipsis overflow-hidden cursor-pointer bg-slate-50">
                        <option value="all" {{ $selectedRtkYear === 'all' ? 'selected' : '' }}>Semua Tahun (Default)</option>
                        @foreach($rtkYearsOptions as $y)
                            <option value="{{ $y }}" {{ (string)$y === (string)$selectedRtkYear ? 'selected' : '' }}>Mulai {{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="p-5 sm:p-6">
                <!-- CHART CONTAINER DENGAN SCROLL VERTICAL -->
                <div class="max-h-[500px] overflow-y-auto custom-scrollbar pr-2 {{ $rtkMasaAktifPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                    <div id="rtkCombinedChartContainer" class="relative w-full" style="min-height: 400px;">
                        <canvas id="rtkCombinedBarChart"></canvas>
                    </div>
                </div>
                
                <!-- EMPTY STATE -->
                <div id="rtkCombinedEmptyState" class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4 {{ $rtkMasaAktifPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                        <i class="fas fa-chart-area text-xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Belum ada data RTK</p>
                    <p class="text-xs text-slate-500 mt-1">Belum ada penyusunan dokumen RTK Provinsi yang tercatat.</p>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 3. DISTRIBUSI E-LEARNING (HORIZONTAL BAR CHART)           -->
        <!-- ========================================================= -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center bg-[#13416B] text-white rounded-xl shrink-0 shadow-sm border border-[#0f3354]">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 font-oswald">Distribusi Pendaftar E-Learning</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Jumlah pengguna terdaftar berdasarkan Provinsi</p>
                    </div>
                </div>

                <div class="w-full sm:w-auto sm:max-w-xs shrink-0">
                    <select id="sdmYearFilter" onchange="fetchSdmPusatData(this.value)" class="w-full text-sm border-slate-200 rounded-lg focus:ring-[#13416B] focus:border-[#13416B] text-ellipsis overflow-hidden cursor-pointer bg-slate-50">
                        @foreach($sdmYears as $year)
                            <option value="{{ $year }}" {{ $selectedSdmYear == $year ? 'selected' : '' }}>Tahun Registrasi {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="p-5 sm:p-6">
                <!-- CHART CONTAINER DENGAN SCROLL VERTICAL -->
                <div class="max-h-[500px] overflow-y-auto custom-scrollbar pr-2 {{ $sdmPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                    <div id="sdmChartWrapper" class="relative w-full" style="min-height: 400px;">
                        <canvas id="sdmHorizontalBarChart"></canvas>
                    </div>
                </div>
                
                <!-- EMPTY STATE -->
                <div id="sdmEmptyState" class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4 {{ $sdmPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
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
            document.addEventListener('DOMContentLoaded', function () {

                // Fungsi untuk memecah teks panjang menjadi array string agar turun baris (Wrap) di Canvas
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
                @if($rtkMasaAktifPerProvinsi->count() > 0)
                    const rtkCombinedCtx = document.getElementById('rtkCombinedBarChart').getContext('2d');
                    
                    const rtkLabelsRaw = @json($rtkMasaAktifPerProvinsi->pluck('province_name'));
                    const rtkLabels = rtkLabelsRaw.map(label => formatMultilineLabel(label));
                    
                    const rtkStartData = @json($rtkMasaAktifPerProvinsi->pluck('start_date'));
                    const rtkEndData = @json($rtkMasaAktifPerProvinsi->pluck('end_date'));

                    // Format data menjadi array [start, end] untuk menampilkan floating bar
                    const floatingData = rtkStartData.map((start, index) => [start, rtkEndData[index]]);

                    window.rtkCombinedChartInstance = new Chart(rtkCombinedCtx, {
                        type: 'bar',
                        data: {
                            labels: rtkLabels,
                            datasets: [
                                {
                                    label: 'Periode Aktif',
                                    data: floatingData,
                                    backgroundColor: '#13416B', // Biru Sirenata
                                    borderRadius: 6,
                                    borderSkipped: false, // Membulatkan sisi kiri dan kanan bar
                                    barPercentage: 0.6,
                                    categoryPercentage: 0.8
                                }
                            ]
                        },
                      options: {
                            indexAxis: 'y', // HORIZONTAL
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false // Sembunyikan legenda karena 1 bar sudah cukup jelas
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(19, 65, 107, 0.95)',
                                    padding: 12,
                                    cornerRadius: 6,
                                    titleFont: { size: 13, weight: 'bold' },
                                    callbacks: {
                                        title: function(context) {
                                            return Array.isArray(context[0].label) ? context[0].label.join(' ') : context[0].label;
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
                                    grid: { color: '#f1f5f9' },
                                    ticks: { 
                                        font: { size: 11 }, 
                                        stepSize: 1,
                                        callback: function(value) {
                                            return value; 
                                        }
                                    }
                                },
                                y: {
                                    grid: { display: false },
                                    ticks: { font: { size: 11, family: "'Inter', sans-serif" }, autoSkip: false },
                                    afterFit: function(scaleInstance) {
                                        scaleInstance.width = window.innerWidth >= 640 ? 160 : 120;
                                    }
                                }
                            }
                        }
                    });

                    // Penyesuaian tinggi canvas dinamis agar tidak gepeng ketika provinsinya banyak
                    const initialRtkHeight = Math.max(400, rtkLabelsRaw.length * 50);
                    document.getElementById('rtkCombinedChartContainer').style.height = initialRtkHeight + 'px';
                @endif

                window.fetchRtkPusatData = function(year) {
                    fetch(`{{ route('admin-pusat.dashboard') }}?rtk_year=${year}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const rtkData = data.rtkMasaAktifPerProvinsi;
                        const containerScroll = document.querySelector('#rtkCombinedChartContainer').parentElement;
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
                            
                            // Map ke format floating bar saat AJAX dirender ulang
                            const newFloatingData = start.map((s, index) => [s, end[index]]);
                            
                            if (window.rtkCombinedChartInstance) {
                                container.style.height = Math.max(400, rawLabels.length * 50) + 'px';

                                window.rtkCombinedChartInstance.data.labels = labels;
                                window.rtkCombinedChartInstance.data.datasets[0].data = newFloatingData;
                                
                                const minYear = Math.min(...start) - 1;
                                const maxYear = Math.max(...end) + 1;
                                window.rtkCombinedChartInstance.options.scales.x.min = isFinite(minYear) ? minYear : 2020;
                                window.rtkCombinedChartInstance.options.scales.x.max = isFinite(maxYear) ? maxYear : 2030;

                                window.rtkCombinedChartInstance.update();
                            }
                        }
                    });
                };

                // =========================================================
                // 2. GRAFIK DISTRIBUSI E-LEARNING (HORIZONTAL BAR) GENDER
                // =========================================================
                let sdmChartInstance = null;

                function renderSdmChart(data) {
                    const wrapperScroll = document.querySelector('#sdmChartWrapper').parentElement;
                    const wrapper = document.getElementById('sdmChartWrapper');
                    const emptyState = document.getElementById('sdmEmptyState');

                    if (!data || data.length === 0) {
                        wrapperScroll.classList.add('hidden');
                        emptyState.classList.remove('hidden');
                        return;
                    }

                    wrapperScroll.classList.remove('hidden');
                    emptyState.classList.add('hidden');

                    // Mengurutkan dari pendaftar total terbanyak
                    const sortedData = [...data].sort((a, b) => b.total - a.total);
                    const rawLabels = sortedData.map(item => item.province_name);
                    const labels = rawLabels.map(label => formatMultilineLabel(label));
                    
                    // Pisahkan data laki-laki dan perempuan
                    const dataMale = sortedData.map(item => item.male);
                    const dataFemale = sortedData.map(item => item.female);

                    // Set tinggi dinamis berdasarkan jumlah provinsi yang muncul
                    const calcHeight = Math.max(400, labels.length * 60);
                    wrapper.style.height = calcHeight + 'px';

                    if (sdmChartInstance) {
                        sdmChartInstance.data.labels = labels;
                        sdmChartInstance.data.datasets[0].data = dataMale;
                        sdmChartInstance.data.datasets[1].data = dataFemale;
                        sdmChartInstance.update();
                    } else {
                        const ctx = document.getElementById('sdmHorizontalBarChart').getContext('2d');
                        sdmChartInstance = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    {
                                        label: 'Laki-Laki',
                                        data: dataMale,
                                        backgroundColor: '#13416B', // Biru Sirenata
                                        borderRadius: 4,
                                        barPercentage: 0.8,
                                        categoryPercentage: 0.7
                                    },
                                    {
                                        label: 'Perempuan',
                                        data: dataFemale,
                                        backgroundColor: '#cbd5e1', // Abu-abu Slate-300 yang sebelumnya dipakai di RTK
                                        borderRadius: 4,
                                        barPercentage: 0.8,
                                        categoryPercentage: 0.7
                                    }
                                ]
                            },
                            options: {
                                indexAxis: 'y', // Menjadikan bar chart horizontal
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { 
                                        display: true,
                                        position: 'bottom',
                                        labels: { 
                                            usePointStyle: true,
                                            boxWidth: 10,
                                            font: { size: 11, family: "'Inter', sans-serif" }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(19, 65, 107, 0.95)',
                                        padding: 12,
                                        cornerRadius: 6,
                                        titleFont: { size: 13, weight: 'bold' },
                                        callbacks: {
                                            title: function(context) {
                                                return Array.isArray(context[0].label) ? context[0].label.join(' ') : context[0].label;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        grid: { color: '#f1f5f9' },
                                        ticks: { font: { size: 11 }, precision: 0 } 
                                    },
                                    y: {
                                        grid: { display: false },
                                        ticks: { font: { size: 11, family: "'Inter', sans-serif" } },
                                        afterFit: function(scaleInstance) {
                                            scaleInstance.width = window.innerWidth >= 640 ? 160 : 120;
                                        }
                                    }
                                }
                            }
                        });
                    }
                }

                // Render pertama kali
                const initialSdmData = @json($sdmPerProvinsi);
                renderSdmChart(initialSdmData);

                // Fungsi AJAX
                window.fetchSdmPusatData = function(year) {
                    fetch(`{{ route('admin-pusat.dashboard') }}?sdm_year=${year}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        renderSdmChart(data.sdmPerProvinsi);
                    });
                };
            });
        </script>
    @endpush
</x-dashboard::layouts.dashboard>