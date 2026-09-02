<x-dashboard::layouts.dashboard title="Dashboard Admin Pusat">
    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto space-y-6 sm:space-y-8 bg-slate-50/50 min-h-screen">
        
        <!-- ===================================== -->
        <!-- 1. STATS GRID (Sirenata Theme)        -->
        <!-- ===================================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            <!-- RTK Disetujui -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">RTK Disetujui</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $rtkStatusDistribution->get('approved', 0) }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Dokumen terverifikasi</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#13416B] text-white flex items-center justify-center shrink-0 shadow-sm border border-[#0f3354]">
                    <i class="fas fa-check-double text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- RTK Menunggu Verifikasi -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">Status Menunggu</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $rtkStatusDistribution->get('pending', 0) }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Butuh peninjauan</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#13416B]/80 text-white flex items-center justify-center shrink-0 shadow-sm border border-[#0f3354]">
                    <i class="fas fa-hourglass-half text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- RTK Ditolak -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">RTK Ditolak</p>
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
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">RTK Berlaku</p>
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
                        <h2 class="text-base font-bold text-slate-800">Komparasi Masa Berlaku RTK</h2>
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
                <!-- CHART CONTAINER -->
                <div id="rtkCombinedChartContainer" class="relative w-full overflow-hidden {{ $rtkMasaAktifPerProvinsi->count() > 0 ? '' : 'hidden' }}" style="min-height: 400px;">
                    <canvas id="rtkCombinedBarChart"></canvas>
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
        <!-- 3. DISTRIBUSI E-LEARNING (LEADERBOARD UI)                 -->
        <!-- ========================================================= -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center bg-[#13416B] text-white rounded-xl shrink-0 shadow-sm border border-[#0f3354]">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Distribusi Pendaftar E-Learning</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Peringkat jumlah pengguna terdaftar berdasarkan Provinsi</p>
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
                <!-- LEADERBOARD CONTAINER -->
                <div id="sdmListContainer" class="space-y-5 max-h-[450px] overflow-y-auto pr-2 custom-scrollbar {{ $sdmPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                    <!-- Di-render via JavaScript agar dinamis -->
                </div>
                
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
                // 1. GRAFIK KOMPARASI RTK (HORIZONTAL)
                // =========================================================
                @if($rtkMasaAktifPerProvinsi->count() > 0)
                    const rtkCombinedCtx = document.getElementById('rtkCombinedBarChart').getContext('2d');
                    
                    const rtkLabelsRaw = @json($rtkMasaAktifPerProvinsi->pluck('province_name'));
                    const rtkLabels = rtkLabelsRaw.map(label => formatMultilineLabel(label));
                    
                    const rtkStartData = @json($rtkMasaAktifPerProvinsi->pluck('start_date'));
                    const rtkEndData = @json($rtkMasaAktifPerProvinsi->pluck('end_date'));

                    window.rtkCombinedChartInstance = new Chart(rtkCombinedCtx, {
                        type: 'bar',
                        data: {
                            labels: rtkLabels,
                            datasets: [
                                {
                                    label: 'Mulai Berlaku',
                                    data: rtkStartData,
                                    backgroundColor: '#cbd5e1', 
                                    borderRadius: 4,
                                    barPercentage: 0.7,
                                    categoryPercentage: 0.8
                                },
                                {
                                    label: 'Masa Berakhir',
                                    data: rtkEndData,
                                    backgroundColor: '#13416B',
                                    borderRadius: 4,
                                    barPercentage: 0.7,
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
                                    position: 'bottom',
                                    labels: { usePointStyle: true, boxWidth: 8, font: { family: "'Inter', sans-serif", size: 11 }, padding: 20 }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
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
                                    min: Math.min(...rtkStartData) - 1, 
                                    max: Math.max(...rtkEndData) + 1,
                                    grid: { color: '#f1f5f9' },
                                    ticks: { 
                                        font: { size: 11 }, 
                                        stepSize: 1,
                                        // MENGHILANGKAN KOMA PADA TAHUN
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

                    const initialRtkHeight = Math.max(400, rtkLabelsRaw.length * 60);
                    document.getElementById('rtkCombinedChartContainer').style.height = initialRtkHeight + 'px';
                @endif

                window.fetchRtkPusatData = function(year) {
                    fetch(`{{ route('admin-pusat.dashboard') }}?rtk_year=${year}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const rtkData = data.rtkMasaAktifPerProvinsi;
                        const container = document.getElementById('rtkCombinedChartContainer');
                        const emptyState = document.getElementById('rtkCombinedEmptyState');
                        
                        if (!rtkData || rtkData.length === 0) {
                            container.classList.add('hidden');
                            emptyState.classList.remove('hidden');
                        } else {
                            container.classList.remove('hidden');
                            emptyState.classList.add('hidden');
                            
                            const rawLabels = rtkData.map(item => item.province_name);
                            const labels = rawLabels.map(label => formatMultilineLabel(label));
                            const start = rtkData.map(item => item.start_date);
                            const end = rtkData.map(item => item.end_date);
                            
                            if (window.rtkCombinedChartInstance) {
                                container.style.height = Math.max(400, rawLabels.length * 60) + 'px';

                                window.rtkCombinedChartInstance.data.labels = labels;
                                window.rtkCombinedChartInstance.data.datasets[0].data = start;
                                window.rtkCombinedChartInstance.data.datasets[1].data = end;
                                
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
                // 2. FUNGSI RENDER LEADERBOARD E-LEARNING
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

                    // Cari nilai tertinggi untuk persentase bar
                    let maxTotal = Math.max(...data.map(item => item.total));
                    if (maxTotal === 0) maxTotal = 1;

                    // Mengurutkan dari yang terbanyak
                    const sortedData = [...data].sort((a, b) => b.total - a.total);

                    let htmlContent = '';
                    sortedData.forEach((item, index) => {
                        const percentage = (item.total / maxTotal) * 100;
                        htmlContent += `
                            <div class="flex items-center gap-3.5 group">
                                <div class="w-6 text-sm font-bold text-slate-400 text-right shrink-0 group-hover:text-[#13416B] transition-colors">${index + 1}.</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-sm font-semibold text-slate-700 truncate pr-2 group-hover:text-[#13416B] transition-colors">${item.province_name}</span>
                                        <span class="text-sm font-extrabold text-[#13416B] shrink-0">${item.total} <span class="text-[10px] font-medium text-slate-500">Peserta</span></span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-[#13416B] h-full rounded-full transition-all duration-700 ease-out" style="width: ${percentage}%"></div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    container.innerHTML = htmlContent;
                }

                // Render pertama kali saat halaman dimuat
                const initialSdmData = @json($sdmPerProvinsi);
                renderSdmLeaderboard(initialSdmData);

                // Fungsi AJAX saat filter SDM diganti
                window.fetchSdmPusatData = function(year) {
                    fetch(`{{ route('admin-pusat.dashboard') }}?sdm_year=${year}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
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