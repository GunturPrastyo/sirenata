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

        <!-- Bar Chart: SDM per Kab/Kota -->
        <div class="bg-white rounded-lg p-3 sm:p-6 shadow-sm mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-0 mb-4 sm:mb-6">
                <h2 class="text-base sm:text-xl font-bold text-gray-900">Jumlah SDM yang Mengambil Kursus per
                    Kabupaten/Kota</h2>
            </div>
            <div class="h-64 sm:h-96 flex justify-center items-center">
                @if($sdmPerKabKota->isEmpty())
                    <div class="flex flex-col justify-center items-center text-gray-500">
                        <i class="fas fa-chart-bar text-4xl mb-3 text-gray-300"></i>
                        <p class="font-medium">Belum Ada Data</p>
                        <p class="text-xs mt-1 text-center">Belum ada user di provinsi ini</p>
                    </div>
                @else
                    <canvas id="sdmBarChart"></canvas>
                @endif
            </div>
        </div>

        <!-- Bar Chart: Masa Aktif RTK per Kab/Kota -->
        <div class="bg-white rounded-lg p-4 md:p-6 shadow-sm mb-4 md:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 md:mb-6">
                <h2 class="text-lg md:text-xl font-bold text-gray-900">Masa Aktif RTK per Kab/Kota</h2>
                <div class="flex items-center gap-2">
                    <label for="rtkYearFilter" class="text-sm font-medium text-gray-600">Tahun:</label>
                    <select id="rtkYearFilter" class="px-3 py-1.5 rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @for($y = (int)date('Y') - 2; $y <= (int)date('Y') + 2; $y++)
                            <option value="{{ $y }}" {{ $y == (int)date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            @if($rtkMasaAktifPerKabKota->count() > 0)
                <div class="h-64 sm:h-80">
                    <canvas id="rtkMasaAktifKabKotaChart"></canvas>
                </div>
            @else
                <div class="h-64 flex flex-col justify-center items-center text-gray-500">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-chart-bar text-5xl text-gray-300"></i>
                        </div>
                        <p class="text-lg font-semibold text-gray-400">Belum Ada Data</p>
                        <p class="text-sm text-gray-400 mt-1">Belum ada RTK Kab/Kota yang aktif di provinsi ini</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pie Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Card 1: RTK Validity Status Pie Chart -->
            <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Status Masa Berlaku RTK
                    ({{ date('Y') }})
                </h2>
                @if($rtkStatusDistribution->sum() > 0)
                    <div class="h-60 sm:h-80 flex items-center justify-center">
                        <canvas id="rtkStatusPieChart"></canvas>
                    </div>
                @else
                    <div class="h-60 sm:h-80 flex flex-col justify-center items-center text-gray-500">
                        <div class="text-center">
                            <div class="mb-4">
                                <i class="fas fa-chart-pie text-5xl text-gray-300"></i>
                            </div>
                            <p class="text-lg font-semibold text-gray-400">Belum Ada Data</p>
                            <p class="text-sm text-gray-400 mt-1">Belum ada data RTK di provinsi ini</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Card 2: Gender Distribution Pie Chart -->
            <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Perbandingan Jenis Kelamin
                </h2>
                <div class="h-60 sm:h-80 flex items-center justify-center">
                    @if ($genderMale == 0 && $genderFemale == 0)
                        <div class="flex flex-col justify-center items-center text-gray-500">
                            <i class="fas fa-chart-pie text-4xl mb-3 text-gray-300"></i>
                            <p class="font-medium">Belum Ada Data</p>
                        </div>
                    @else
                        <canvas id="genderPieChart"></canvas>
                    @endif
                </div>
            </div>

            <!-- Card 3: Module Distribution Pie Chart -->
            <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Perbandingan Modul yang
                    Diambil</h2>
                <div class="h-60 sm:h-80 flex flex-col justify-center items-center text-gray-500">
                    <i class="fas fa-book-open text-4xl mb-3 text-gray-300"></i>
                    <p class="font-medium">Belum Tersedia</p>
                    <p class="text-xs mt-1 text-center">Data modul dan enrollment belum tersedia di database</p>
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

            // Generate 3-color alternating pattern (Blue-Red-Green)
            function generateAlternatingColors(dataLength) {
                const colors = [
                    { bg: 'rgba(59, 130, 246, 0.8)', border: 'rgba(59, 130, 246, 1)', hover: 'rgba(59, 130, 246, 1)' },   // Blue
                    { bg: 'rgba(239, 68, 68, 0.8)', border: 'rgba(239, 68, 68, 1)', hover: 'rgba(239, 68, 68, 1)' },     // Red
                    { bg: 'rgba(34, 197, 94, 0.8)', border: 'rgba(34, 197, 94, 1)', hover: 'rgba(34, 197, 94, 1)' }      // Green
                ];

                const bgColors = [];
                const borderColors = [];
                const hoverColors = [];

                for (let i = 0; i < dataLength; i++) {
                    const colorIndex = i % 3;
                    bgColors.push(colors[colorIndex].bg);
                    borderColors.push(colors[colorIndex].border);
                    hoverColors.push(colors[colorIndex].hover);
                }

                return { bgColors, borderColors, hoverColors };
            }

            // Bar Chart: SDM per Kab/Kota
            if (document.getElementById('sdmBarChart')) {
                const barCtx = document.getElementById('sdmBarChart').getContext('2d');
                const initialColors = generateAlternatingColors(sdmData.length);

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