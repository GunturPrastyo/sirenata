<x-dashboard::layouts.dashboard title="Dashboard Admin Pusat">
    <div class="p-2 sm:p-6">
        <div class="mb-5">
            <h1 class="text-2xl font-bold">Halo {{ $user->name }}</h1>

            <p class="text-sm text-gray-500">Login Sebagai {{ $user->getRoleNames()->implode(', ') }}</p>
        </div>
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[['label' => 'Dashboard']]" />


        <!-- Card: Informasi RTK (Paling Atas) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 sm:mb-8 overflow-hidden transition-all hover:shadow-md">
            <div class="bg-gradient-to-r from-blue-50/50 to-transparent px-5 sm:px-8 py-4 sm:py-5 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg"><i class="fas fa-file-signature"></i></div>
                    Informasi RTK
                </h2>
            </div>
            
            <div class="p-5 sm:p-8">
                <!-- Masa Aktif RTK (Top) -->
                <div class="mb-8 bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 bg-blue-500 rounded-full"></div>
                            <h3 class="text-lg font-bold text-slate-800">Masa Aktif RTK per Provinsi</h3>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                            <label for="rtkYearFilter" class="text-sm font-semibold text-slate-600 pl-2 cursor-pointer">
                                <i class="far fa-calendar-alt mr-1"></i> Tahun
                            </label>
                            <select id="rtkYearFilter" onchange="fetchRtkPusatData(this.value)" class="bg-white border-0 ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500 rounded-md py-1.5 pl-3 pr-8 text-sm font-medium text-slate-700 cursor-pointer shadow-sm">
                                <option value="all" {{ $selectedRtkYear === 'all' ? 'selected' : '' }}>Semua Data Aktif (Default)</option>
                                @foreach($rtkYearsOptions as $y)
                                    <option value="{{ $y }}" {{ (string)$y === (string)$selectedRtkYear ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div id="rtkChartContainer" class="relative h-72 sm:h-[400px] w-full {{ $rtkMasaAktifPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                        <canvas id="rtkMasaAktifBarChart"></canvas>
                    </div>
                    
                    <div id="rtkEmptyState" class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 {{ $rtkMasaAktifPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                            <i class="fas fa-chart-bar text-2xl text-slate-300"></i>
                        </div>
                        <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                        <p class="text-sm mt-1">Belum ada RTK Provinsi yang aktif dengan start date tahun terpilih</p>
                    </div>
                </div>

                <!-- Masa Berakhir RTK (Middle) -->
                <div class="mb-8 bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 bg-pink-500 rounded-full"></div>
                            <h3 class="text-lg font-bold text-slate-800">Masa Berakhir RTK per Provinsi</h3>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                            <label for="rtkEndYearFilter" class="text-sm font-semibold text-slate-600 pl-2 cursor-pointer">
                                <i class="far fa-calendar-check mr-1"></i> Tahun Akhir
                            </label>
                            <select id="rtkEndYearFilter" onchange="fetchRtkPusatEndData(this.value)" class="bg-white border-0 ring-1 ring-slate-200 focus:ring-2 focus:ring-pink-500 rounded-md py-1.5 pl-3 pr-8 text-sm font-medium text-slate-700 cursor-pointer shadow-sm">
                                <option value="all" {{ $selectedRtkEndYear === 'all' ? 'selected' : '' }}>Semua Data (Default)</option>
                                @foreach($rtkEndYearsOptions as $y)
                                    <option value="{{ $y }}" {{ (string)$y === (string)$selectedRtkEndYear ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div id="rtkEndChartContainer" class="relative h-72 sm:h-[400px] w-full {{ $rtkMasaBerakhirPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                        <canvas id="rtkMasaBerakhirBarChart"></canvas>
                    </div>
                    
                    <div id="rtkEndEmptyState" class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 {{ $rtkMasaBerakhirPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                            <i class="fas fa-chart-bar text-2xl text-slate-300"></i>
                        </div>
                        <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                        <p class="text-sm mt-1">Belum ada RTK Provinsi yang aktif dengan end date tahun terpilih</p>
                    </div>
                </div>

                <!-- Periode Waktu RTK (Butterfly Chart) -->
                <div class="mb-8 bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-2 h-8 bg-purple-500 rounded-full"></div>
                        <h3 class="text-lg font-bold text-slate-800">Visualisasi Periode RTK (Butterfly Chart)</h3>
                    </div>
                    
                    <div id="rtkButterflyChartContainer" class="relative h-[400px] sm:h-[500px] w-full {{ $rtkMasaAktifPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                        <canvas id="rtkButterflyBarChart"></canvas>
                    </div>
                    
                    <div id="rtkButterflyEmptyState" class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 {{ $rtkMasaAktifPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                            <i class="fas fa-chart-line text-2xl text-slate-300"></i>
                        </div>
                        <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                        <p class="text-sm mt-1">Belum ada RTK aktif untuk divisualisasikan</p>
                    </div>
                </div>

                <!-- RTK Stats (Bottom Grid) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    <!-- Status Masa Berlaku RTK -->
                    <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-indigo-500 rounded-full"></div>
                            <h3 class="text-base font-bold text-slate-800">Status Masa Berlaku RTK ({{ date('Y') }})</h3>
                        </div>
                        
                        @if($rtkStatusDistribution->sum() > 0)
                            <div class="relative h-64 sm:h-80 w-full flex items-center justify-center">
                                <canvas id="rtkStatusPieChart"></canvas>
                            </div>
                        @else
                            <div class="h-64 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                    <i class="fas fa-chart-pie text-2xl text-slate-300"></i>
                                </div>
                                <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                                <p class="text-sm mt-1 text-center">Belum ada data RTK yang tercatat</p>
                            </div>
                        @endif
                    </div>

                    <!-- RTK Berlaku -->
                    <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                            <h3 class="text-base font-bold text-slate-800">RTK Berlaku Saat Ini</h3>
                        </div>
                        
                        <div class="h-64 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                <i class="fas fa-file-contract text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-500">Belum Tersedia</p>
                            <p class="text-sm mt-1 text-center">Data informasi RTK aktif belum tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Informasi E-Learning -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 sm:mb-8 overflow-hidden transition-all hover:shadow-md">
            <div class="bg-gradient-to-r from-emerald-50/50 to-transparent px-5 sm:px-8 py-4 sm:py-5 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg"><i class="fas fa-laptop-code"></i></div>
                    Informasi E-Learning
                </h2>
            </div>
            
            <div class="p-5 sm:p-8">
                <!-- SDM User -->
                <div class="mb-8 bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 bg-emerald-500 rounded-full"></div>
                            <h3 class="text-lg font-bold text-slate-800">Jumlah SDM (User) per Provinsi</h3>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                            <label for="sdmYearFilter" class="text-sm font-semibold text-slate-600 pl-2 cursor-pointer">
                                <i class="far fa-calendar-alt mr-1"></i> Tahun
                            </label>
                            <select id="sdmYearFilter" onchange="fetchSdmPusatData(this.value)" class="bg-white border-0 ring-1 ring-slate-200 focus:ring-2 focus:ring-emerald-500 rounded-md py-1.5 pl-3 pr-8 text-sm font-medium text-slate-700 cursor-pointer shadow-sm">
                                @foreach($sdmYears as $year)
                                    <option value="{{ $year }}" {{ $selectedSdmYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div id="sdmChartContainer" class="relative h-72 sm:h-[400px] w-full {{ $sdmPerProvinsi->count() > 0 ? '' : 'hidden' }}">
                        <canvas id="sdmBarChart"></canvas>
                    </div>
                    
                    <div id="sdmEmptyState" class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200 {{ $sdmPerProvinsi->count() > 0 ? 'hidden' : '' }}">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                            <i class="fas fa-users text-2xl text-slate-300"></i>
                        </div>
                        <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                        <p class="text-sm mt-1">Belum ada user dengan data provinsi di tahun terpilih</p>
                    </div>
                </div>

                <!-- E-Learning Stats (Bottom Grid) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    <!-- Gender -->
                    <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-pink-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-pink-500 rounded-full"></div>
                            <h3 class="text-base font-bold text-slate-800">Perbandingan Jenis Kelamin</h3>
                        </div>
                        
                        @if($genderMale + $genderFemale > 0)
                            <div class="relative h-64 sm:h-80 w-full flex items-center justify-center">
                                <canvas id="genderPieChart"></canvas>
                            </div>
                        @else
                            <div class="h-64 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                    <i class="fas fa-venus-mars text-2xl text-slate-300"></i>
                                </div>
                                <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                                <p class="text-sm mt-1 text-center">Data jenis kelamin user belum diisi</p>
                            </div>
                        @endif
                    </div>

                    <!-- Modul -->
                    <div class="bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                            <h3 class="text-base font-bold text-slate-800">Perbandingan Modul yang Diambil</h3>
                        </div>
                        
                        <div class="h-64 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                <i class="fas fa-book-open text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-500">Belum Tersedia</p>
                            <p class="text-sm mt-1 text-center">Data modul belum tersedia di sistem</p>
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
                    // Bar Chart: SDM per Provinsi (data dari database)
                    const barCtx = document.getElementById('sdmBarChart').getContext('2d');

                    const sdmLabels = @json($sdmPerProvinsi->pluck('province_name'));
                    const sdmData = @json($sdmPerProvinsi->pluck('total'));

                    // Function to generate sharp colors based on average
                    window.generateGradientColors = function(data) {
                        if (data.length === 0) return { bgColors: [], borderColors: [], hoverColors: [] };
                        const total = data.reduce((a, b) => a + b, 0);
                        const avg = total / data.length;

                        const bgColors = [], borderColors = [], hoverColors = [];

                        data.forEach(val => {
                            const ratio = avg === 0 ? 1 : val / avg;
                            let r, g, b;

                            if (ratio <= 0.3) {
                                // Sangat Sedikit -> Merah Pekat
                                r = 220; g = 38; b = 38;
                            } else if (ratio <= 0.6) {
                                // Sedikit -> Merah Standard
                                r = 239; g = 68; b = 68;
                            } else if (ratio <= 0.9) {
                                // Agak Sedikit -> Merah Pudar
                                r = 248; g = 113; b = 113;
                            } else if (ratio <= 1.1) {
                                // Mendekati/Sama Rata-rata -> Biru
                                r = 59; g = 130; b = 246;
                            } else if (ratio <= 1.4) {
                                // Agak Banyak -> Hijau Muda
                                r = 74; g = 222; b = 128;
                            } else if (ratio <= 1.7) {
                                // Banyak -> Hijau Standard
                                r = 34; g = 197; b = 94;
                            } else {
                                // Sangat Banyak -> Hijau Pekat
                                r = 22; g = 163; b = 74;
                            }

                            bgColors.push(`rgba(${r}, ${g}, ${b}, 0.95)`);
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
                                    backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleFont: { size: 14, weight: 'bold' },
                                    bodyFont: { size: 13 }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        font: { size: 12 },
                                        stepSize: 1
                                    },
                                    grid: { color: 'rgba(229, 231, 235, 0.8)' }
                                },
                                x: {
                                    ticks: { font: { size: 12 } },
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                @endif
                
                window.fetchSdmPusatData = function(year) {
                    fetch(`{{ route('admin-pusat.dashboard') }}?sdm_year=${year}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
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
                            
                            const colors = window.generateGradientColors ? window.generateGradientColors(totals) : { bgColors: Array(totals.length).fill('rgba(34, 197, 94, 0.95)'), borderColors: Array(totals.length).fill('rgba(34, 197, 94, 1)'), hoverColors: Array(totals.length).fill('rgba(34, 197, 94, 1)') };
                            
                            if (window.sdmBarChartInstance) {
                                window.sdmBarChartInstance.data.labels = labels;
                                window.sdmBarChartInstance.data.datasets[0].data = totals;
                                window.sdmBarChartInstance.data.datasets[0].backgroundColor = colors.bgColors;
                                window.sdmBarChartInstance.data.datasets[0].borderColor = colors.borderColors;
                                window.sdmBarChartInstance.data.datasets[0].hoverBackgroundColor = colors.hoverColors;
                                window.sdmBarChartInstance.update();
                            } else {
                                const barCtx = document.getElementById('sdmBarChart').getContext('2d');
                                window.sdmBarChartInstance = new Chart(barCtx, {
                                    type: 'bar',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Jumlah User',
                                            data: totals,
                                            backgroundColor: colors.bgColors,
                                            borderColor: colors.borderColors,
                                            borderWidth: 1,
                                            borderRadius: 6,
                                            hoverBackgroundColor: colors.hoverColors
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(31, 41, 55, 0.95)', padding: 12, cornerRadius: 8, titleFont: { size: 14, weight: 'bold' }, bodyFont: { size: 13 } } },
                                        scales: { y: { beginAtZero: true, ticks: { font: { size: 12 }, stepSize: 1 }, grid: { color: 'rgba(229, 231, 235, 0.8)' } }, x: { ticks: { font: { size: 12 } }, grid: { display: false } } }
                                    }
                                });
                            }
                        }
                    });
                };


                    @if($genderMale + $genderFemale > 0)
                        // Pie Chart: Gender Distribution (data dari database)
                        const genderCtx = document.getElementById('genderPieChart').getContext('2d');
                        new Chart(genderCtx, {
                            type: 'pie',
                            data: {
                                labels: ['Laki-laki', 'Perempuan'],
                                datasets: [{
                                    data: [{{ $genderMale }}, {{ $genderFemale }}],
                                    backgroundColor: [
                                        'rgba(59, 130, 246, 0.8)',
                                        'rgba(236, 72, 153, 0.8)'
                                    ],
                                    borderColor: [
                                        'rgba(59, 130, 246, 1)',
                                        'rgba(236, 72, 153, 1)'
                                    ],
                                    borderWidth: 2,
                                    hoverOffset: 10
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { padding: 20, font: { size: 13, weight: '500' } }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                        padding: 12,
                                        cornerRadius: 8,
                                        titleFont: { size: 14, weight: 'bold' },
                                        bodyFont: { size: 13 },
                                        callbacks: {
                                            label: function (context) {
                                                const label = context.label || '';
                                                const value = context.parsed || 0;
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = ((value / total) * 100).toFixed(1);
                                                return `${label}: ${value} (${percentage}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    @endif

                @if($rtkMasaAktifPerProvinsi->count() > 0)
                    const currentYear = {{ date('Y') }};
                    const rtkLabels = @json($rtkMasaAktifPerProvinsi->pluck('province_name'));
                    const rtkEndDatesRaw = @json($rtkMasaAktifPerProvinsi->pluck('end_date'));
                    const rtkStartDates = @json($rtkMasaAktifPerProvinsi->pluck('start_date'));

                    let maxEndDate = Math.max(...rtkEndDatesRaw.map(v => parseInt(v)));
                    let initialMinYear = '{{ $selectedRtkYear }}' === 'all' ? currentYear - 1 : parseInt('{{ $selectedRtkYear }}');

                    window.getRtkColors = function(endDates, prop, curY) {
                        return endDates.map(ed => {
                            const sisa = parseInt(ed) - curY;
                            const alpha = prop === 'bg' ? '0.8' : '1';
                            if (sisa > 2) return `rgba(34, 197, 94, ${alpha})`;
                            if (sisa >= 1) return `rgba(245, 158, 11, ${alpha})`;
                            return `rgba(239, 68, 68, ${alpha})`;
                        });
                    };

                    const rtkBarCtx = document.getElementById('rtkMasaAktifBarChart').getContext('2d');
                    window.rtkMasaAktifChartInstance = new Chart(rtkBarCtx, {
                        type: 'bar',
                        data: {
                            labels: rtkLabels,
                            datasets: [{
                                label: 'Masa Berlaku s/d',
                                data: rtkEndDatesRaw.map(v => parseInt(v)),
                                backgroundColor: window.getRtkColors(rtkEndDatesRaw, 'bg', currentYear),
                                borderColor: window.getRtkColors(rtkEndDatesRaw, 'border', currentYear),
                                borderWidth: 1,
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(ctx) {
                                            const i = ctx.dataIndex;
                                            const start = window.rtkMasaAktifChartInstance.customRtkStartDates[i];
                                            const end = window.rtkMasaAktifChartInstance.customRtkEndDates[i];
                                            return [
                                                `Periode: ${start} - ${end}`,
                                                `Sisa Masa Berlaku: ${Math.max(0, parseInt(end) - currentYear)} tahun`
                                            ];
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    min: initialMinYear,
                                    max: maxEndDate,
                                    title: { display: true, text: 'Tahun', font: { size: 12 } },
                                    ticks: { stepSize: 1, font: { size: 12 }, callback: v => v },
                                    grid: { color: 'rgba(229, 231, 235, 0.8)' }
                                },
                                x: {
                                    ticks: { font: { size: 10 }, maxRotation: 45, minRotation: 45 },
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                    window.rtkMasaAktifChartInstance.customRtkStartDates = rtkStartDates;
                    window.rtkMasaAktifChartInstance.customRtkEndDates = rtkEndDatesRaw;
                @endif
                
                @if($rtkMasaBerakhirPerProvinsi->count() > 0)
                    const rtkEndLabels = @json($rtkMasaBerakhirPerProvinsi->pluck('province_name'));
                    const rtkEndChartEndDatesRaw = @json($rtkMasaBerakhirPerProvinsi->pluck('end_date'));
                    const rtkEndStartDates = @json($rtkMasaBerakhirPerProvinsi->pluck('start_date'));

                    let maxEndChartEndDate = Math.max(...rtkEndChartEndDatesRaw.map(v => parseInt(v)));
                    let initialEndMinYear = '{{ $selectedRtkEndYear }}' === 'all' ? currentYear - 1 : parseInt('{{ $selectedRtkEndYear }}') - 1;
                    let initialEndMaxYear = '{{ $selectedRtkEndYear }}' === 'all' ? maxEndChartEndDate : parseInt('{{ $selectedRtkEndYear }}') + 1;

                    if (document.getElementById('rtkMasaBerakhirBarChart')) {
                        const rtkEndBarCtx = document.getElementById('rtkMasaBerakhirBarChart').getContext('2d');
                        window.rtkMasaBerakhirChartInstance = new Chart(rtkEndBarCtx, {
                            type: 'bar',
                            data: {
                                labels: rtkEndLabels,
                                datasets: [{
                                    label: 'Masa Berlaku s/d',
                                    data: rtkEndChartEndDatesRaw.map(v => parseInt(v)),
                                    backgroundColor: window.getRtkColors ? window.getRtkColors(rtkEndChartEndDatesRaw, 'bg', currentYear) : [],
                                    borderColor: window.getRtkColors ? window.getRtkColors(rtkEndChartEndDatesRaw, 'border', currentYear) : [],
                                    borderWidth: 1,
                                    borderRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                        padding: 12,
                                        cornerRadius: 8,
                                        callbacks: {
                                            label: function(ctx) {
                                                const i = ctx.dataIndex;
                                                const start = window.rtkMasaBerakhirChartInstance.customRtkStartDates[i];
                                                const end = window.rtkMasaBerakhirChartInstance.customRtkEndDates[i];
                                                return [
                                                    `Periode: ${start} - ${end}`,
                                                    `Sisa Masa Berlaku: ${Math.max(0, parseInt(end) - currentYear)} tahun`
                                                ];
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        min: initialEndMinYear,
                                        max: initialEndMaxYear,
                                        title: { display: true, text: 'Tahun', font: { size: 12 } },
                                        ticks: { stepSize: 1, font: { size: 12 }, callback: v => v },
                                        grid: { color: 'rgba(229, 231, 235, 0.8)' }
                                    },
                                    x: {
                                        ticks: { font: { size: 10 }, maxRotation: 45, minRotation: 45 },
                                        grid: { display: false }
                                    }
                                }
                            }
                        });
                        window.rtkMasaBerakhirChartInstance.customRtkStartDates = rtkEndStartDates;
                        window.rtkMasaBerakhirChartInstance.customRtkEndDates = rtkEndChartEndDatesRaw;
                    }

                    if (document.getElementById('rtkButterflyBarChart')) {
                        const rtkButterflyCtx = document.getElementById('rtkButterflyBarChart').getContext('2d');
                        
                        const pastData = rtkEndChartEndDatesRaw.map((end, i) => {
                            const start = parseInt(rtkEndStartDates[i]);
                            const e = parseInt(end);
                            if (start >= currentYear) return [0, 0];
                            const endY = Math.min(currentYear, e);
                            return [start - currentYear, endY - currentYear];
                        });

                        const futureData = rtkEndChartEndDatesRaw.map((end, i) => {
                            const start = parseInt(rtkEndStartDates[i]);
                            const e = parseInt(end);
                            if (e <= currentYear) return [0, 0];
                            const startY = Math.max(currentYear, start);
                            return [startY - currentYear, e - currentYear];
                        });

                        const allStartsArray = rtkEndStartDates.map(s => parseInt(s));
                        const allEndsArray = rtkEndChartEndDatesRaw.map(e => parseInt(e));
                        const globalMinStart = Math.min(...allStartsArray);
                        const globalMaxEnd = Math.max(...allEndsArray);
                        const xAxisMin = (globalMinStart - 1) - currentYear;
                        const xAxisMax = (globalMaxEnd + 1) - currentYear;

                        window.rtkButterflyChartInstance = new Chart(rtkButterflyCtx, {
                            type: 'bar',
                            data: {
                                labels: rtkEndLabels,
                                datasets: [
                                    {
                                        label: 'Masa Berjalan',
                                        data: pastData,
                                        backgroundColor: 'rgba(245, 158, 11, 0.8)',
                                        borderColor: 'rgba(217, 119, 6, 1)',
                                        borderWidth: 1,
                                        borderRadius: 4,
                                        borderSkipped: false
                                    },
                                    {
                                        label: 'Masa Depan',
                                        data: futureData,
                                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                        borderColor: 'rgba(5, 150, 105, 1)',
                                        borderWidth: 1,
                                        borderRadius: 4,
                                        borderSkipped: false
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                indexAxis: 'y',
                                plugins: {
                                    legend: { display: true, position: 'bottom' },
                                    tooltip: {
                                        backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                        callbacks: {
                                            label: function(ctx) {
                                                const arr = ctx.raw;
                                                const startY = currentYear + arr[0];
                                                const endY = currentYear + arr[1];
                                                if (startY === endY) return null;
                                                return `${ctx.dataset.label}: ${startY} - ${endY}`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        min: xAxisMin,
                                        max: xAxisMax,
                                        ticks: {
                                            stepSize: 1,
                                            font: { size: 11, weight: '500' },
                                            callback: function(val) {
                                                return currentYear + parseInt(val);
                                            }
                                        },
                                        grid: {
                                            color: function(context) {
                                                if (context.tick.value === 0) return 'rgba(239, 68, 68, 0.8)';
                                                return 'rgba(229, 231, 235, 0.8)';
                                            },
                                            lineWidth: function(context) {
                                                if (context.tick.value === 0) return 2;
                                                return 1;
                                            }
                                        }
                                    },
                                    y: {
                                        stacked: true,
                                        grid: { display: false }
                                    }
                                }
                            }
                        });
                    }
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
                        const chartContainer = document.getElementById('rtkChartContainer');
                        const emptyState = document.getElementById('rtkEmptyState');
                        
                        if (!rtkData || rtkData.length === 0) {
                            chartContainer.classList.add('hidden');
                            emptyState.classList.remove('hidden');
                        } else {
                            chartContainer.classList.remove('hidden');
                            emptyState.classList.add('hidden');
                            
                            const labels = rtkData.map(item => item.province_name);
                            const endDatesRaw = rtkData.map(item => item.end_date);
                            const startDates = rtkData.map(item => item.start_date);
                            const currentYear = {{ date('Y') }};
                            
                            let maxEndDate = Math.max(...endDatesRaw.map(v => parseInt(v)));
                            let newMinYear = year === 'all' ? currentYear - 1 : parseInt(year) - 1;
                            
                            window.getRtkColors = window.getRtkColors || function(endDates, prop, curY) {
                                return endDates.map(ed => {
                                    const sisa = parseInt(ed) - curY;
                                    const alpha = prop === 'bg' ? '0.8' : '1';
                                    if (sisa > 2) return `rgba(34, 197, 94, ${alpha})`;
                                    if (sisa >= 1) return `rgba(245, 158, 11, ${alpha})`;
                                    return `rgba(239, 68, 68, ${alpha})`;
                                });
                            };

                            const bgColors = window.getRtkColors(endDatesRaw, 'bg', currentYear);
                            const borderColors = window.getRtkColors(endDatesRaw, 'border', currentYear);
                            const dataVals = endDatesRaw.map(v => parseInt(v));

                            if (window.rtkMasaAktifChartInstance) {
                                window.rtkMasaAktifChartInstance.data.labels = labels;
                                window.rtkMasaAktifChartInstance.data.datasets[0].data = dataVals;
                                window.rtkMasaAktifChartInstance.data.datasets[0].backgroundColor = bgColors;
                                window.rtkMasaAktifChartInstance.data.datasets[0].borderColor = borderColors;
                                window.rtkMasaAktifChartInstance.options.scales.y.min = newMinYear;
                                window.rtkMasaAktifChartInstance.options.scales.y.max = maxEndDate;
                                window.rtkMasaAktifChartInstance.customRtkStartDates = startDates;
                                window.rtkMasaAktifChartInstance.customRtkEndDates = endDatesRaw;
                                window.rtkMasaAktifChartInstance.update();

                                if (window.rtkButterflyChartInstance) {
                                    const newPastData = endDatesRaw.map((end, i) => {
                                        const start = parseInt(startDates[i]);
                                        const e = parseInt(end);
                                        if (start >= currentYear) return [0, 0];
                                        const endY = Math.min(currentYear, e);
                                        return [start - currentYear, endY - currentYear];
                                    });

                                    const newFutureData = endDatesRaw.map((end, i) => {
                                        const start = parseInt(startDates[i]);
                                        const e = parseInt(end);
                                        if (e <= currentYear) return [0, 0];
                                        const startY = Math.max(currentYear, start);
                                        return [startY - currentYear, e - currentYear];
                                    });
                                    
                                    const newMinStart = Math.min(...startDates.map(s => parseInt(s)));
                                    const newMaxEnd = Math.max(...endDatesRaw.map(e => parseInt(e)));
                                    window.rtkButterflyChartInstance.options.scales.x.min = (newMinStart - 1) - currentYear;
                                    window.rtkButterflyChartInstance.options.scales.x.max = (newMaxEnd + 1) - currentYear;

                                    window.rtkButterflyChartInstance.data.labels = labels;
                                    window.rtkButterflyChartInstance.data.datasets[0].data = newPastData;
                                    window.rtkButterflyChartInstance.data.datasets[1].data = newFutureData;
                                    window.rtkButterflyChartInstance.update();
                                }
                            } else {
                                const rtkBarCtx = document.getElementById('rtkMasaAktifBarChart').getContext('2d');
                                window.rtkMasaAktifChartInstance = new Chart(rtkBarCtx, {
                                    type: 'bar',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Masa Berlaku s/d',
                                            data: dataVals,
                                            backgroundColor: bgColors,
                                            borderColor: borderColors,
                                            borderWidth: 1,
                                            borderRadius: 6
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: { display: false },
                                            tooltip: {
                                                backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                                padding: 12,
                                                cornerRadius: 8,
                                                callbacks: {
                                                    label: function(ctx) {
                                                        const i = ctx.dataIndex;
                                                        const start = window.rtkMasaAktifChartInstance.customRtkStartDates[i];
                                                        const end = window.rtkMasaAktifChartInstance.customRtkEndDates[i];
                                                        return [
                                                            `Periode: ${start} - ${end}`,
                                                            `Sisa Masa Berlaku: ${Math.max(0, parseInt(end) - currentYear)} tahun`
                                                        ];
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                min: newMinYear,
                                                max: maxEndDate,
                                                title: { display: true, text: 'Tahun', font: { size: 12 } },
                                                ticks: { stepSize: 1, font: { size: 12 }, callback: v => v },
                                                grid: { color: 'rgba(229, 231, 235, 0.8)' }
                                            },
                                            x: {
                                                ticks: { font: { size: 10 }, maxRotation: 45, minRotation: 45 },
                                                grid: { display: false }
                                            }
                                        }
                                    }
                                });
                                window.rtkMasaAktifChartInstance.customRtkStartDates = startDates;
                                window.rtkMasaAktifChartInstance.customRtkEndDates = endDatesRaw;
                            }
                        }
                    });
                };

                @if($rtkStatusDistribution->sum() > 0)
                    // Pie Chart: Status Distribusi RTK
                    const statusLabels = {
                        'pending': 'Menunggu Persetujuan',
                        'approved': 'Disetujui',
                        'rejected': 'Ditolak',
                        'expired': 'Kadaluarsa'
                    };
                    const statusColors = {
                        'pending': 'rgba(245, 158, 11, 0.8)',
                        'approved': 'rgba(34, 197, 94, 0.8)',
                        'rejected': 'rgba(239, 68, 68, 0.8)',
                        'expired': 'rgba(156, 163, 175, 0.8)'
                    };
                    const statusBorders = {
                        'pending': 'rgba(245, 158, 11, 1)',
                        'approved': 'rgba(34, 197, 94, 1)',
                        'rejected': 'rgba(239, 68, 68, 1)',
                        'expired': 'rgba(156, 163, 175, 1)'
                    };

                    const rtkStatusRaw = @json($rtkStatusDistribution);
                    const rtkStatusKeys = Object.keys(rtkStatusRaw);
                    const rtkStatusData = Object.values(rtkStatusRaw);
                    const rtkStatusLabels = rtkStatusKeys.map(k => statusLabels[k] || k);
                    const rtkStatusBgColors = rtkStatusKeys.map(k => statusColors[k] || 'rgba(156, 163, 175, 0.8)');
                    const rtkStatusBorderColors = rtkStatusKeys.map(k => statusBorders[k] || 'rgba(156, 163, 175, 1)');

                    const rtkStatusCtx = document.getElementById('rtkStatusPieChart').getContext('2d');
                    new Chart(rtkStatusCtx, {
                        type: 'pie',
                        data: {
                            labels: rtkStatusLabels,
                            datasets: [{
                                data: rtkStatusData,
                                backgroundColor: rtkStatusBgColors,
                                borderColor: rtkStatusBorderColors,
                                borderWidth: 2,
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { padding: 20, font: { size: 13, weight: '500' } }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(ctx) {
                                            const label = ctx.label || '';
                                            const value = ctx.parsed || 0;
                                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                            const pct = ((value / total) * 100).toFixed(1);
                                            return `${label}: ${value} (${pct}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                @endif

                window.fetchRtkPusatEndData = function(year) {
                    fetch(`{{ route('admin-pusat.dashboard') }}?rtk_end_year=${year}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const rtkData = data.rtkMasaBerakhirPerProvinsi;
                        const chartContainer = document.getElementById('rtkEndChartContainer');
                        const emptyState = document.getElementById('rtkEndEmptyState');
                        
                        if (!rtkData || rtkData.length === 0) {
                            chartContainer.classList.add('hidden');
                            emptyState.classList.remove('hidden');
                        } else {
                            chartContainer.classList.remove('hidden');
                            emptyState.classList.add('hidden');
                            
                            const labels = rtkData.map(item => item.province_name);
                            const endDatesRaw = rtkData.map(item => item.end_date);
                            const startDates = rtkData.map(item => item.start_date);
                            const currentYear = {{ date('Y') }};
                            
                            let maxEndDate = Math.max(...endDatesRaw.map(v => parseInt(v)));
                            let newMinYear = year === 'all' ? currentYear - 1 : parseInt(year) - 1;
                            let newMaxYear = year === 'all' ? maxEndDate : parseInt(year) + 1;
                            
                            window.getRtkColors = window.getRtkColors || function(endDates, prop, curY) {
                                return endDates.map(ed => {
                                    const sisa = parseInt(ed) - curY;
                                    const alpha = prop === 'bg' ? '0.8' : '1';
                                    if (sisa > 2) return `rgba(34, 197, 94, ${alpha})`;
                                    if (sisa >= 1) return `rgba(245, 158, 11, ${alpha})`;
                                    return `rgba(239, 68, 68, ${alpha})`;
                                });
                            };

                            const bgColors = window.getRtkColors(endDatesRaw, 'bg', currentYear);
                            const borderColors = window.getRtkColors(endDatesRaw, 'border', currentYear);
                            const dataVals = endDatesRaw.map(v => parseInt(v));

                            if (window.rtkMasaBerakhirChartInstance) {
                                window.rtkMasaBerakhirChartInstance.data.labels = labels;
                                window.rtkMasaBerakhirChartInstance.data.datasets[0].data = dataVals;
                                window.rtkMasaBerakhirChartInstance.data.datasets[0].backgroundColor = bgColors;
                                window.rtkMasaBerakhirChartInstance.data.datasets[0].borderColor = borderColors;
                                window.rtkMasaBerakhirChartInstance.options.scales.y.min = newMinYear;
                                window.rtkMasaBerakhirChartInstance.options.scales.y.max = newMaxYear;
                                window.rtkMasaBerakhirChartInstance.customRtkStartDates = startDates;
                                window.rtkMasaBerakhirChartInstance.customRtkEndDates = endDatesRaw;
                                window.rtkMasaBerakhirChartInstance.update();
                            } else {
                                if (document.getElementById('rtkMasaBerakhirBarChart')) {
                                    const rtkBarCtx = document.getElementById('rtkMasaBerakhirBarChart').getContext('2d');
                                    window.rtkMasaBerakhirChartInstance = new Chart(rtkBarCtx, {
                                        type: 'bar',
                                        data: {
                                            labels: labels,
                                            datasets: [{
                                                label: 'Masa Berlaku s/d',
                                                data: dataVals,
                                                backgroundColor: bgColors,
                                                borderColor: borderColors,
                                                borderWidth: 1,
                                                borderRadius: 6
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: { display: false },
                                                tooltip: {
                                                    backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                                    padding: 12,
                                                    cornerRadius: 8,
                                                    callbacks: {
                                                        label: function(ctx) {
                                                            const i = ctx.dataIndex;
                                                            const start = window.rtkMasaBerakhirChartInstance.customRtkStartDates[i];
                                                            const end = window.rtkMasaBerakhirChartInstance.customRtkEndDates[i];
                                                            return [
                                                                `Periode: ${start} - ${end}`,
                                                                `Sisa Masa Berlaku: ${Math.max(0, parseInt(end) - currentYear)} tahun`
                                                            ];
                                                        }
                                                    }
                                                }
                                            },
                                            scales: {
                                                y: {
                                                    min: newMinYear,
                                                    max: newMaxYear,
                                                    title: { display: true, text: 'Tahun', font: { size: 12 } },
                                                    ticks: { stepSize: 1, font: { size: 12 }, callback: v => v },
                                                    grid: { color: 'rgba(229, 231, 235, 0.8)' }
                                                },
                                                x: {
                                                    ticks: { font: { size: 10 }, maxRotation: 45, minRotation: 45 },
                                                    grid: { display: false }
                                                }
                                            }
                                        }
                                    });
                                    window.rtkMasaBerakhirChartInstance.customRtkStartDates = startDates;
                                    window.rtkMasaBerakhirChartInstance.customRtkEndDates = endDatesRaw;
                                }
                            }
                        }
                    });
                };

                });
        </script>
    @endpush

</x-dashboard::layouts.dashboard>