<x-dashboard::layouts.dashboard title="Dashboard Admin Kab/Kota">
    <div class="p-2 sm:p-6">
        @if (!$user->hasCompleteScope())
            <div class="mb-4 rounded-lg bg-yellow-50 border border-yellow-300 p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z" />
                    </svg>

                    <div>
                        <h1 class="font-semibold text-yellow-800">
                            Data Tidak Dapat Ditampilkan
                        </h1>
                        <p class="text-sm text-yellow-700">
                            Wilayah Kabupaten/Kota untuk akun ini belum ditetapkan,
                            sehingga data tidak dapat dimuat. Silakan hubungi Admin Pusat
                            untuk pengaturan wilayah.
                        </p>
                    </div>
                </div>
            </div>
        @endif
        <div class="mb-5">
            <h1 class="text-2xl font-bold">Halo {{ $user->name }}</h1>

            <p class="text-sm text-gray-500">Login Sebagai {{ $user->getRoleNames()->implode(', ') }} -
                {{ $user->scopeArea?->regency?->name }}
            </p>
        </div>

        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :items="[['label' => 'Dashboard']]" />

        <!-- Card: Informasi E-Learning -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 sm:mb-8 overflow-hidden transition-all hover:shadow-md">
            <div class="bg-gradient-to-r from-emerald-50/50 to-transparent px-5 sm:px-8 py-4 sm:py-5 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg"><i class="fas fa-laptop-code"></i></div>
                    Informasi E-Learning
                </h2>
            </div>
            
            <div class="p-5 sm:p-8">
                <!-- Line Chart: SDM per Waktu -->
                <div class="mb-8 bg-white border border-slate-100 rounded-xl p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 bg-emerald-500 rounded-full"></div>
                            <h3 class="text-lg font-bold text-slate-800">Jumlah SDM yang Mengambil Kursus per Tahun</h3>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-50 p-1.5 rounded-lg border border-slate-200">
                            <label for="periodFilter" class="text-sm font-semibold text-slate-600 pl-2 cursor-pointer">
                                <i class="far fa-calendar-alt mr-1"></i> Tahun
                            </label>
                            <select id="periodFilter" onchange="window.location.href='{{ route('admin-kab-kota.dashboard') }}?year=' + this.value" class="bg-white border-0 ring-1 ring-slate-200 focus:ring-2 focus:ring-emerald-500 rounded-md py-1.5 pl-3 pr-8 text-sm font-medium text-slate-700 cursor-pointer shadow-sm">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    @if(empty($sdmPerTahun) || array_sum($sdmPerTahun) === 0)
                        <div class="h-72 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                <i class="fas fa-chart-line text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                            <p class="text-sm mt-1">Belum ada registrasi user pada rentang tahun ini</p>
                        </div>
                    @else
                        <div class="relative h-72 sm:h-[400px] w-full">
                            <canvas id="rtkLineChart"></canvas>
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
                        
                        @if ($courses->isEmpty())
                            <div class="h-64 flex flex-col justify-center items-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                                    <i class="fas fa-book-open text-2xl text-slate-300"></i>
                                </div>
                                <p class="text-base font-semibold text-slate-500">Belum Ada Data</p>
                                <p class="text-sm mt-1 text-center">Belum ada user yang mengambil modul/course</p>
                            </div>
                        @else
                            <div class="relative h-64 sm:h-80 w-full flex items-center justify-center">
                                <canvas id="courseDoughnutChart"></canvas>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Data for SDM per Waktu
            const sdmTahunDataRaw = @json($sdmPerTahun);
            const lineLabels = Object.keys(sdmTahunDataRaw);
            const lineData = Object.values(sdmTahunDataRaw);

            if (document.getElementById('rtkLineChart')) {
                const lineCtx = document.getElementById('rtkLineChart').getContext('2d');
                new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: lineLabels,
                        datasets: [{
                            label: 'Jumlah SDM',
                            data: lineData,
                            borderColor: 'rgba(99, 102, 241, 1)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: 'rgba(99, 102, 241, 1)',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3
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
                                },
                                callbacks: {
                                    label: function (context) {
                                        return `Jumlah SDM: ${context.parsed.y.toLocaleString()}`;
                                    }
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
                                    callback: function (value) {
                                        if (Number.isInteger(value)) {
                                            return value.toLocaleString();
                                        }
                                        return null;
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
                                        size: 12
                                    }
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

            // Doughnut Chart: Course Distribution
            if (document.getElementById('courseDoughnutChart')) {
                const courseCtx = document.getElementById('courseDoughnutChart').getContext('2d');
                const courseDataRaw = @json($courses);
                const courseLabels = Object.keys(courseDataRaw);
                const courseData = Object.values(courseDataRaw);
                
                const backgroundColors = [
                    'rgba(245, 158, 11, 0.8)',  // Amber
                    'rgba(99, 102, 241, 0.8)',  // Indigo
                    'rgba(16, 185, 129, 0.8)',  // Emerald
                    'rgba(244, 63, 94, 0.8)',   // Rose
                    'rgba(59, 130, 246, 0.8)',   // Blue
                    'rgba(139, 92, 246, 0.8)',  // Purple
                    'rgba(249, 115, 22, 0.8)',  // Orange
                    'rgba(6, 182, 212, 0.8)',   // Cyan
                    'rgba(236, 72, 153, 0.8)',  // Pink
                    'rgba(14, 165, 233, 0.8)'   // Sky
                ];
                
                const borderColors = [
                    'rgba(245, 158, 11, 1)',
                    'rgba(99, 102, 241, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(244, 63, 94, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(139, 92, 246, 1)',
                    'rgba(249, 115, 22, 1)',
                    'rgba(6, 182, 212, 1)',
                    'rgba(236, 72, 153, 1)',
                    'rgba(14, 165, 233, 1)'
                ];

                new Chart(courseCtx, {
                    type: 'doughnut',
                    data: {
                        labels: courseLabels,
                        datasets: [{
                            data: courseData,
                            backgroundColor: backgroundColors.slice(0, courseLabels.length),
                            borderColor: borderColors.slice(0, courseLabels.length),
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
                                        size: 11,
                                        weight: '500'
                                    },
                                    boxWidth: 12
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
                                        return `${label}: ${value} user (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            }
        </script>
    @endpush
</x-dashboard::layouts.dashboard>