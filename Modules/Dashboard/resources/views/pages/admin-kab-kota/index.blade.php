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
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-kab-kota.dashboard') }}"
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

        <!-- Line Chart: SDM per Tahun -->
        <div class="bg-white rounded-lg p-3 sm:p-6 shadow-sm mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-0 mb-4 sm:mb-6">
                <h2 class="text-base sm:text-xl font-bold text-gray-900">Jumlah SDM yang Mengambil Kursus per Tahun</h2>
                <div class="flex items-center gap-2">
                    <label for="periodFilter" class="text-sm font-medium text-gray-600">Periode:</label>
                    <select id="periodFilter" onchange="window.location.href='{{ route('admin-kab-kota.dashboard') }}?period_start=' + this.value"
                        class="px-3 py-1.5 rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($periods as $period)
                            <option value="{{ $period['start'] }}" {{ $selectedPeriodStart == $period['start'] ? 'selected' : '' }}>{{ $period['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="h-64 sm:h-96">
                @if(empty($sdmPerTahun))
                    <div class="flex flex-col justify-center items-center h-full text-gray-500">
                        <i class="fas fa-chart-line text-4xl mb-3 text-gray-300"></i>
                        <p class="font-medium">Belum Ada Data</p>
                        <p class="text-xs mt-1 text-center">Belum ada registrasi user di kabupaten/kota ini</p>
                    </div>
                @else
                    <canvas id="rtkLineChart"></canvas>
                @endif
            </div>
        </div>

        <!-- Pie Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Left Card: Gender Distribution Pie Chart -->
            <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Perbandingan Jenis Kelamin</h2>
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

            <!-- Right Card: Module Distribution Pie Chart -->
            <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Perbandingan Modul yang Diambil
                </h2>
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
            // Data for SDM per Tahun
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
        </script>
    @endpush
</x-dashboard::layouts.dashboard>