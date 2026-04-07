<x-dashboard::layouts.dashboard title="Dashboard Admin Provinsi">
    <div class="p-2 sm:p-6">
        @if (!$user->hasCompleteScope())
            <div class="mb-4 rounded-lg bg-yellow-50 border border-yellow-300 p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z" />
                    </svg>

                    <div>
                        <h1 class="font-semibold text-yellow-800">
                            Wilayah Provinsi Belum Ditetapkan
                        </h1>
                        <p class="text-sm text-yellow-700">
                            Akun ini belum memiliki penetapan wilayah provinsi pada sistem.
                            Untuk melanjutkan pengelolaan data, silakan hubungi Admin Pusat
                            agar wilayah dapat dikonfigurasi terlebih dahulu.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-5">
            <h1 class="text-2xl font-bold">Halo {{ $user->name }}</h1>

            <p class="text-sm text-gray-500">Login Sebagai {{ $user->getRoleNames()->implode(', ') }} -
                {{ $user->scopeArea?->province?->name ?? 'Belum Ditetapkan' }}
            </p>
        </div>
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-province.dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Dashboard</span>
                    </div>
                </li>
            </ol>
        </nav>

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
                            <h3 class="text-lg font-bold text-slate-800">Masa Aktif RTK per Kab/Kota</h3>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                            <label for="rtkYearFilter" class="text-sm font-semibold text-slate-600 pl-2 cursor-pointer">
                                <i class="far fa-calendar-alt mr-1"></i> Tahun
                            </label>
                            <select id="rtkYearFilter" class="bg-white border-0 ring-1 ring-slate-200 focus:ring-2 focus:ring-blue-500 rounded-md py-1.5 pl-3 pr-8 text-sm font-medium text-slate-700 cursor-pointer shadow-sm">
                                @for($y = (int)date('Y') - 2; $y <= (int)date('Y') + 2; $y++)
                                    <option value="{{ $y }}" {{ $y == (int)date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    @if($rtkMasaAktifPerKabKota->count() > 0)
                        <div class="relative h-72 sm:h-[400px] w-full">
                            <canvas id="rtkMasaAktifKabKotaChart"></canvas>
                        </div>
                    @else
                        <div class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                <i class="fas fa-chart-bar text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                            <p class="text-sm mt-1">Belum ada RTK Kab/Kota yang aktif di provinsi ini</p>
                        </div>
                    @endif
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
                                <p class="text-sm mt-1 text-center">Belum ada data RTK di provinsi ini</p>
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
                            <h3 class="text-lg font-bold text-slate-800">Jumlah SDM yang Mengambil Kursus per Kabupaten/Kota</h3>
                        </div>
                    </div>
                    
                    @if($sdmPerKabKota->isEmpty())
                        <div class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                <i class="fas fa-chart-bar text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                            <p class="text-sm mt-1">Belum ada user di provinsi ini</p>
                        </div>
                    @else
                        <div class="relative h-72 sm:h-[400px] w-full">
                            <canvas id="sdmBarChart"></canvas>
                        </div>
                    @endif
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
                        
                        @if ($genderMale == 0 && $genderFemale == 0)
                            <div class="h-64 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                    <i class="fas fa-venus-mars text-2xl text-slate-300"></i>
                                </div>
                                <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                                <p class="text-sm mt-1 text-center">Data jenis kelamin user belum diisi</p>
                            </div>
                        @else
                            <div class="relative h-64 sm:h-80 w-full flex items-center justify-center">
                                <canvas id="genderPieChart"></canvas>
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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Data for SDM per Kab/Kota
            const sdmLabels = @json($sdmPerKabKota->pluck('regency_name'));
            const sdmData = @json($sdmPerKabKota->pluck('total'));

            function generateGradientColors(data) {
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

            // Bar Chart: SDM per Kab/Kota
            if (document.getElementById('sdmBarChart')) {
                const barCtx = document.getElementById('sdmBarChart').getContext('2d');
                const initialColors = generateGradientColors(sdmData);

                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: sdmLabels,
                        datasets: [{
                            label: 'Jumlah SDM',
                            data: sdmData,
                            backgroundColor: initialColors.bgColors,
                            borderColor: initialColors.borderColors,
                            borderWidth: 1,
                            borderRadius: 6,
                            hoverBackgroundColor: initialColors.hoverColors
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
                                backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: 12
                                    },
                                    stepSize: 1
                                },
                                grid: {
                                    color: 'rgba(229, 231, 235, 0.8)'
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 10
                                    },
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Pie Chart: Gender Distribution
            if (document.getElementById('genderPieChart')) {
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
                                labels: {
                                    padding: 20,
                                    font: {
                                        size: 13,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(31, 41, 55, 0.95)',
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                },
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Bar Chart: Masa Aktif RTK per Kab/Kota
            @if($rtkMasaAktifPerKabKota->count() > 0)
                const currentYear = {{ date('Y') }};
                let rtkBaseYear = currentYear;
                let rtkMaxYear = rtkBaseYear + 4;
                const rtkLabels = @json($rtkMasaAktifPerKabKota->pluck('regency_name'));
                const rtkEndDatesRaw = @json($rtkMasaAktifPerKabKota->pluck('end_date'));
                const rtkStartDates = @json($rtkMasaAktifPerKabKota->pluck('start_date'));
                const rtkEndDates = rtkEndDatesRaw.map(ed => ed);

                function getRtkBarData(baseYear) {
                    const max = baseYear + 4;
                    return rtkEndDatesRaw.map(ed => Math.max(baseYear, Math.min(parseInt(ed), max)));
                }
                function getRtkColors(prop) {
                    return rtkEndDatesRaw.map(ed => {
                        const sisa = parseInt(ed) - currentYear;
                        const alpha = prop === 'bg' ? '0.8' : '1';
                        if (sisa > 2) return `rgba(34, 197, 94, ${alpha})`;
                        if (sisa >= 1) return `rgba(245, 158, 11, ${alpha})`;
                        return `rgba(239, 68, 68, ${alpha})`;
                    });
                }

                if (document.getElementById('rtkMasaAktifKabKotaChart')) {
                    const rtkBarCtx = document.getElementById('rtkMasaAktifKabKotaChart').getContext('2d');
                    const rtkMasaAktifChart = new Chart(rtkBarCtx, {
                        type: 'bar',
                        data: {
                            labels: rtkLabels,
                            datasets: [{
                                label: 'Masa Berlaku s/d',
                                data: getRtkBarData(rtkBaseYear),
                                backgroundColor: getRtkColors('bg'),
                                borderColor: getRtkColors('border'),
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
                                            return [
                                                `Periode: ${rtkStartDates[i]} - ${rtkEndDates[i]}`,
                                                `Sisa Masa Berlaku: ${Math.max(0, parseInt(rtkEndDates[i]) - currentYear)} tahun`
                                            ];
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    min: rtkBaseYear,
                                    max: rtkMaxYear,
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

                    document.getElementById('rtkYearFilter').addEventListener('change', function() {
                        rtkBaseYear = parseInt(this.value);
                        rtkMaxYear = rtkBaseYear + 4;
                        rtkMasaAktifChart.data.datasets[0].data = getRtkBarData(rtkBaseYear);
                        rtkMasaAktifChart.options.scales.y.min = rtkBaseYear;
                        rtkMasaAktifChart.options.scales.y.max = rtkMaxYear;
                        rtkMasaAktifChart.update();
                    });
                }
            @endif

            // Pie Chart: Status Distribusi RTK
            @if($rtkStatusDistribution->sum() > 0)
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

                if (document.getElementById('rtkStatusPieChart')) {
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
                }
            @endif
        </script>
    @endpush
</x-dashboard::layouts.dashboard>