<x-dashboard::layouts.dashboard title="Dashboard Admin Pusat">
    <div class="p-4 sm:p-8 space-y-8 bg-slate-50/50 min-h-screen">
        <!-- Welcome Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Halo, {{ $user->name }}</h1>
                <p class="text-sm text-slate-500 mt-0.5">Login Sebagai <span class="font-medium text-blue-600">{{ $user->getRoleNames()->implode(', ') }}</span></p>
            </div>
            <div>
                <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[['label' => 'Dashboard']]" />
            </div>
        </div>

        <!-- Card: Informasi RTK -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden transition-all">
            <div class="px-6 sm:px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="fas fa-file-signature text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Informasi RTK</h2>
                        <p class="text-xs text-slate-500">Statistik dan masa aktif Rencana Tenaga Kerja</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 sm:p-8 space-y-8">
                <!-- Masa Aktif RTK -->
                <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                            <h3 class="text-base font-bold text-slate-900">Masa Aktif RTK per Provinsi</h3>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                            <label for="rtkYearFilter" class="text-xs font-semibold text-slate-600 pl-2 cursor-pointer flex items-center gap-1.5">
                                <i class="far fa-calendar-alt text-slate-400"></i> Tahun
                            </label>
                            <select id="rtkYearFilter" onchange="fetchRtkPusatData(this.value)" class="bg-white border-0 ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500 rounded-md py-1.5 pl-3 pr-8 text-xs font-semibold text-slate-700 cursor-pointer shadow-sm">
                                <option value="all" {{ $selectedRtkYear === 'all' ? 'selected' : '' }}>Semua Data Aktif (Default)</option>
                                @foreach($rtkYearsOptions as $y)
                                    <option value="{{ $y }}" {{ (string)$y === (string)$selectedRtkYear ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div id="rtkChartContainer" class="relative h-72 sm:h-[380px] w-full {{ $rtkMasaAktifPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                        <canvas id="rtkMasaAktifBarChart"></canvas>
                    </div>
                    
                    <div id="rtkEmptyState" class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 {{ $rtkMasaAktifPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm mb-3">
                            <i class="fas fa-chart-bar text-xl text-slate-300"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Belum Ada Data</p>
                        <p class="text-xs text-slate-400 mt-0.5">Belum ada RTK Provinsi yang aktif dengan start date tahun terpilih</p>
                    </div>
                </div>

                <!-- Masa Berakhir RTK -->
                <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-pink-600 rounded-full"></div>
                            <h3 class="text-base font-bold text-slate-900">Masa Berakhir RTK per Provinsi</h3>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                            <label for="rtkEndYearFilter" class="text-xs font-semibold text-slate-600 pl-2 cursor-pointer flex items-center gap-1.5">
                                <i class="far fa-calendar-check text-slate-400"></i> Tahun Akhir
                            </label>
                            <select id="rtkEndYearFilter" onchange="fetchRtkPusatEndData(this.value)" class="bg-white border-0 ring-1 ring-slate-200 focus:ring-2 focus:ring-pink-500 rounded-md py-1.5 pl-3 pr-8 text-xs font-semibold text-slate-700 cursor-pointer shadow-sm">
                                <option value="all" {{ $selectedRtkEndYear === 'all' ? 'selected' : '' }}>Semua Data (Default)</option>
                                @foreach($rtkEndYearsOptions as $y)
                                    <option value="{{ $y }}" {{ (string)$y === (string)$selectedRtkEndYear ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div id="rtkEndChartContainer" class="relative h-72 sm:h-[380px] w-full {{ $rtkMasaBerakhirPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                        <canvas id="rtkMasaBerakhirBarChart"></canvas>
                    </div>
                    
                    <div id="rtkEndEmptyState" class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 {{ $rtkMasaBerakhirPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm mb-3">
                            <i class="fas fa-chart-bar text-xl text-slate-300"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Belum Ada Data</p>
                        <p class="text-xs text-slate-400 mt-0.5">Belum ada RTK Provinsi yang aktif dengan end date tahun terpilih</p>
                    </div>
                </div>

                <!-- Periode Waktu RTK (Butterfly Chart) -->
                <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1.5 h-6 bg-purple-600 rounded-full"></div>
                        <h3 class="text-base font-bold text-slate-900">Visualisasi Periode RTK (Butterfly Chart)</h3>
                    </div>
                    
                    <div id="rtkButterflyChartContainer" class="relative h-[380px] sm:h-[450px] w-full {{ $rtkMasaAktifPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                        <canvas id="rtkButterflyBarChart"></canvas>
                    </div>
                    
                    <div id="rtkButterflyEmptyState" class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 {{ $rtkMasaAktifPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm mb-3">
                            <i class="fas fa-chart-line text-xl text-slate-300"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Belum Ada Data</p>
                        <p class="text-xs text-slate-400 mt-0.5">Belum ada RTK aktif untuk divisualisasikan</p>
                    </div>
                </div>

                <!-- RTK Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status Masa Berlaku RTK -->
                    <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-5 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-sm font-bold text-slate-900">Status Masa Berlaku RTK ({{ date('Y') }})</h3>
                        </div>
                        
                        @if($rtkStatusDistribution->sum() > 0)
                            <div class="relative h-64 sm:h-72 w-full flex items-center justify-center">
                                <canvas id="rtkStatusPieChart"></canvas>
                            </div>
                        @else
                            <div class="h-60 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm mb-3">
                                    <i class="fas fa-chart-pie text-xl text-slate-300"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Belum Ada Data</p>
                                <p class="text-xs text-slate-400 mt-0.5">Belum ada data RTK yang tercatat</p>
                            </div>
                        @endif
                    </div>

                    <!-- RTK Berlaku -->
                    <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-5 bg-emerald-600 rounded-full"></div>
                            <h3 class="text-sm font-bold text-slate-900">RTK Berlaku Saat Ini</h3>
                        </div>
                        
                        <div class="h-60 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm mb-3">
                                <i class="fas fa-file-contract text-xl text-slate-300"></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-600">Belum Tersedia</p>
                            <p class="text-xs text-slate-400 mt-0.5">Data informasi RTK aktif belum tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Informasi E-Learning -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden transition-all">
            <div class="px-6 sm:px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                        <i class="fas fa-laptop-code text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Informasi E-Learning</h2>
                        <p class="text-xs text-slate-500">Statistik pengguna dan modul pelatihan</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 sm:p-8 space-y-8">
                <!-- SDM User -->
                <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-emerald-600 rounded-full"></div>
                            <h3 class="text-base font-bold text-slate-900">Jumlah SDM (User) per Provinsi</h3>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                            <label for="sdmYearFilter" class="text-xs font-semibold text-slate-600 pl-2 cursor-pointer flex items-center gap-1.5">
                                <i class="far fa-calendar-alt text-slate-400"></i> Tahun
                            </label>
                            <select id="sdmYearFilter" onchange="fetchSdmPusatData(this.value)" class="bg-white border-0 ring-1 ring-slate-200 focus:ring-2 focus:ring-emerald-500 rounded-md py-1.5 pl-3 pr-8 text-xs font-semibold text-slate-700 cursor-pointer shadow-sm">
                                @foreach($sdmYears as $year)
                                    <option value="{{ $year }}" {{ $selectedSdmYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div id="sdmChartContainer" class="relative h-72 sm:h-[380px] w-full {{ $sdmPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                        <canvas id="sdmBarChart"></canvas>
                    </div>
                    
                    <div id="sdmEmptyState" class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 {{ $sdmPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm mb-3">
                            <i class="fas fa-users text-xl text-slate-300"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Belum Ada Data</p>
                        <p class="text-xs text-slate-400 mt-0.5">Belum ada user dengan data provinsi di tahun terpilih</p>
                    </div>
                </div>

                <!-- E-Learning Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Gender -->
                    <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-5 bg-pink-600 rounded-full"></div>
                            <h3 class="text-sm font-bold text-slate-900">Perbandingan Jenis Kelamin</h3>
                        </div>
                        
                        @if($genderMale + $genderFemale > 0)
                            <div class="relative h-64 sm:h-72 w-full flex items-center justify-center">
                                <canvas id="genderPieChart"></canvas>
                            </div>
                        @else
                            <div class="h-60 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm mb-3">
                                    <i class="fas fa-venus-mars text-xl text-slate-300"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Belum Ada Data</p>
                                <p class="text-xs text-slate-400 mt-0.5">Data jenis kelamin user belum diisi</p>
                            </div>
                        @endif
                    </div>

                    <!-- Modul -->
                    <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-5 bg-amber-500 rounded-full"></div>
                            <h3 class="text-sm font-bold text-slate-900">Perbandingan Modul yang Diambil</h3>
                        </div>
                        
                        <div class="h-60 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm mb-3">
                                <i class="fas fa-book-open text-xl text-slate-300"></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-600">Belum Tersedia</p>
                            <p class="text-xs text-slate-400 mt-0.5">Data modul belum tersedia di sistem</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                @if($sdmPerProvinsi->count() > 0)
                    const barCtx = document.getElementById('sdmBarChart').getContext('2d');
                    const sdmLabels = @json($sdmPerProvinsi->pluck('province_name'));
                    const sdmData = @json($sdmPerProvinsi->pluck('total'));

                    window.generateGradientColors = function(data) {
                        if (data.length === 0) return { bgColors: [], borderColors: [], hoverColors: [] };
                        const total = data.reduce((a, b) => a + b, 0);
                        const avg = total / data.length;
                        const bgColors = [], borderColors = [], hoverColors = [];

                        data.forEach(val => {
                            const ratio = avg === 0 ? 1 : val / avg;
                            let r, g, b;

                            if (ratio <= 0.3) { r = 220; g = 38; b = 38; }
                            else if (ratio <= 0.6) { r = 239; g = 68; b = 68; }
                            else if (ratio <= 0.9) { r = 248; g = 113; b = 113; }
                            else if (ratio <= 1.1) { r = 59; g = 130; b = 246; }
                            else if (ratio <= 1.4) { r = 74; g = 222; b = 128; }
                            else if (ratio <= 1.7) { r = 34; g = 197; b = 94; }
                            else { r = 22; g = 163; b = 74; }

                            bgColors.push(`rgba(${r}, ${g}, ${b}, 0.9)`);
                            borderColors.push(`rgba(${r}, ${g}, ${b}, 1)`);
                            hoverColors.push(`rgba(${r}, ${g}, ${b}, 1)`);
                        });
                        return { bgColors, borderColors, hoverColors };
                    }

                    const barColors = window.generateGradientColors(sdmData);

                    window.sdmBarChartInstance = new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: sdmLabels,
                            datasets: [{
                                label: 'Jumlah User',
                                data: sdmData,
                                backgroundColor: barColors.bgColors,
                                borderColor: barColors.borderColors,
                                borderWidth: 1,
                                borderRadius: 6,
                                hoverBackgroundColor: barColors.hoverColors
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleFont: { size: 13, weight: 'bold' },
                                    bodyFont: { size: 12 }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { font: { size: 11 }, stepSize: 1 },
                                    grid: { color: 'rgba(241, 245, 249, 1)' }
                                },
                                x: {
                                    ticks: { font: { size: 11 } },
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                @endif
                
                window.fetchSdmPusatData = function(year) {
                    fetch(`{{ route('admin-pusat.dashboard') }}?sdm_year=${year}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const sdmData = data.sdmPerProvinsi;
                        const chartContainer = document.getElementById('sdmChartContainer');
                        const emptyState = document.getElementById('sdmEmptyState');
                        
                        if (!sdmData || sdmData.length === 0) {
                            chartContainer.classList.add('hidden');
                            emptyState.classList.remove('hidden');
                        } else {
                            chartContainer.classList.remove('hidden');
                            emptyState.classList.add('hidden');
                            
                            const labels = sdmData.map(item => item.province_name);
                            const totals = sdmData.map(item => item.total);
                            const colors = window.generateGradientColors ? window.generateGradientColors(totals) : { bgColors: [], borderColors: [], hoverColors: [] };
                            
                            if (window.sdmBarChartInstance) {
                                window.sdmBarChartInstance.data.labels = labels;
                                window.sdmBarChartInstance.data.datasets[0].data = totals;
                                window.sdmBarChartInstance.data.datasets[0].backgroundColor = colors.bgColors;
                                window.sdmBarChartInstance.data.datasets[0].borderColor = colors.borderColors;
                                window.sdmBarChartInstance.update();
                            }
                        }
                    });
                };

                @if($genderMale + $genderFemale > 0)
                    const genderCtx = document.getElementById('genderPieChart').getContext('2d');
                    new Chart(genderCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Laki-laki', 'Perempuan'],
                            datasets: [{
                                data: [{{ $genderMale }}, {{ $genderFemale }}],
                                backgroundColor: ['rgba(59, 130, 246, 0.85)', 'rgba(236, 72, 153, 0.85)'],
                                borderWidth: 0,
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
                                    labels: { padding: 20, font: { size: 12, weight: '500' }, usePointStyle: true, pointStyle: 'circle' }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function (context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return ` ${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                @endif

                @if($rtkStatusDistribution->sum() > 0)
                    const statusLabels = { 'pending': 'Menunggu', 'approved': 'Disetujui', 'rejected': 'Ditolak', 'expired': 'Kadaluarsa' };
                    const statusColors = { 'pending': '#f59e0b', 'approved': '#10b981', 'rejected': '#ef4444', 'expired': '#94a3b8' };

                    const rtkStatusRaw = @json($rtkStatusDistribution);
                    const rtkStatusKeys = Object.keys(rtkStatusRaw);
                    const rtkStatusData = Object.values(rtkStatusRaw);
                    const rtkStatusLabels = rtkStatusKeys.map(k => statusLabels[k] || k);
                    const rtkStatusBgColors = rtkStatusKeys.map(k => statusColors[k] || '#94a3b8');

                    const rtkStatusCtx = document.getElementById('rtkStatusPieChart').getContext('2d');
                    new Chart(rtkStatusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: rtkStatusLabels,
                            datasets: [{
                                data: rtkStatusData,
                                backgroundColor: rtkStatusBgColors,
                                borderWidth: 0,
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
                                    labels: { padding: 20, font: { size: 12, weight: '500' }, usePointStyle: true, pointStyle: 'circle' }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(ctx) {
                                            const label = ctx.label || '';
                                            const value = ctx.parsed || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const pct = ((value / total) * 100).toFixed(1);
                                            return ` ${label}: ${value} (${pct}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                @endif

            });
        </script>
    @endpush
</x-dashboard::layouts.dashboard>