<x-dashboard::layouts.dashboard title="Dashboard Admin Pusat">
    <div class="p-2 sm:p-6">
        <div class="mb-5">
            <h1 class="text-2xl font-bold">Halo {{ $user->name }}</h1>

            <p class="text-sm text-gray-500">Login Sebagai {{ $user->getRoleNames()->implode(', ') }}</p>
        </div>
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-pusat.dashboard') }}"
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

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-5 mb-6 sm:mb-8 stats-grid">
            <!-- Total Admin Pusat -->
            <x-dashboard::superadmin.statscard title="Total Admin Pusat" count="12" growth="2 baru"
                period="Bulan ini">
                <div class="p-2 md:p-3 rounded-full gradient-indigo">
                    <i class="fas fa-users text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.statscard>


            <!-- Admin Aktif -->
            <x-dashboard::superadmin.statscard title="Admin Aktif" count="10" growth="83% dari total"
                period="Bulan ini">
                <div class="p-2 md:p-3 rounded-full gradient-emerald">
                    <i class="fas fa-user-check text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.statscard>

            <!-- Admin Nonaktif -->
            <x-dashboard::superadmin.statscard title="Admin Nonaktif" count="5" growth="17% dari total"
                period="Bulan ini">
                <div class="p-2 md:p-3 rounded-full gradient-amber">
                    <i class="fas fa-user-slash text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.statscard>

            <!-- Aktivitas Admin -->
            <x-dashboard::superadmin.statscard title="Aktivitas Admin" count="247" growth="17% dari total"
                period="Bulan ini">
                <div class="p-2 md:p-3 rounded-full gradient-rose">
                    <i class="fas fa-chart-line text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.statscard>
        </div>

        <!-- Bar Chart: SDM per Provinsi -->
        <div class="bg-white rounded-lg p-3 sm:p-6 shadow-sm mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-0 mb-4 sm:mb-6">
                <h2 class="text-base sm:text-xl font-bold text-gray-900">Jumlah SDM yang Mengambil Kursus per Provinsi
                </h2>
                <div class="flex items-center gap-2">
                    <label for="yearFilter" class="hidden sm:inline text-sm font-medium text-gray-700">Tahun:</label>
                    <select id="yearFilter"
                        class=" py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                        <option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                        <option value="{{ date('Y') - 2 }}">{{ date('Y') - 2 }}</option>
                        <option value="{{ date('Y') - 3 }}">{{ date('Y') - 3 }}</option>
                    </select>
                </div>
            </div>
            <div class="h-64 sm:h-96">
                <canvas id="sdmBarChart"></canvas>
            </div>
        </div>

        <!-- Pie Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Left Card: Gender Distribution Pie Chart -->
            <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Perbandingan Jenis Kelamin
                </h2>
                <div class="h-60 sm:h-80 flex items-center justify-center">
                    <canvas id="genderPieChart"></canvas>
                </div>
            </div>

            <!-- Right Card: Module Distribution Pie Chart -->
            <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Perbandingan Modul yang
                    Diambil</h2>
                <div class="h-60 sm:h-80 flex items-center justify-center">
                    <canvas id="modulePieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            console.log('Chart.js loaded');
            document.addEventListener('DOMContentLoaded', function() {
                // Chart data by year - 38 Provinces
                const chartDataByYear = {
                    2022: {
                        labels: ['Aceh', 'Sumut', 'Sumsel', 'Sumbar', 'Bengkulu', 'Riau', 'Kep. Riau', 'Jambi',
                            'Lampung', 'Babel',
                            'Kalbar', 'Kaltim', 'Kalsel', 'Kalteng', 'Kaltara', 'Banten', 'DKI Jakarta',
                            'Jabar', 'Jateng', 'DIY',
                            'Jatim', 'Bali', 'NTT', 'NTB', 'Gorontalo', 'Sulbar', 'Sulteng', 'Sulut', 'Sultra',
                            'Sulsel',
                            'Malut', 'Maluku', 'Papua Barat', 'Papua', 'Papua Tengah', 'Papua Pegunungan',
                            'Papua Selatan', 'Papua Barat Daya'
                        ],
                        data: [245, 312, 289, 276, 198, 265, 156, 187, 234, 145,
                            198, 276, 234, 189, 123, 298, 456, 567, 498, 234,
                            534, 312, 167, 198, 134, 156, 178, 234, 189, 345,
                            123, 145, 98, 112, 87, 76, 89, 94
                        ]
                    },
                    2023: {
                        labels: ['Aceh', 'Sumut', 'Sumsel', 'Sumbar', 'Bengkulu', 'Riau', 'Kep. Riau', 'Jambi',
                            'Lampung', 'Babel',
                            'Kalbar', 'Kaltim', 'Kalsel', 'Kalteng', 'Kaltara', 'Banten', 'DKI Jakarta',
                            'Jabar', 'Jateng', 'DIY',
                            'Jatim', 'Bali', 'NTT', 'NTB', 'Gorontalo', 'Sulbar', 'Sulteng', 'Sulut', 'Sultra',
                            'Sulsel',
                            'Malut', 'Maluku', 'Papua Barat', 'Papua', 'Papua Tengah', 'Papua Pegunungan',
                            'Papua Selatan', 'Papua Barat Daya'
                        ],
                        data: [289, 356, 324, 298, 234, 298, 187, 219, 267, 172,
                            234, 312, 267, 218, 145, 334, 523, 645, 567, 276,
                            612, 356, 198, 234, 156, 187, 206, 267, 219, 389,
                            145, 172, 115, 134, 102, 89, 105, 112
                        ]
                    },
                    2024: {
                        labels: ['Aceh', 'Sumut', 'Sumsel', 'Sumbar', 'Bengkulu', 'Riau', 'Kep. Riau', 'Jambi',
                            'Lampung', 'Babel',
                            'Kalbar', 'Kaltim', 'Kalsel', 'Kalteng', 'Kaltara', 'Banten', 'DKI Jakarta',
                            'Jabar', 'Jateng', 'DIY',
                            'Jatim', 'Bali', 'NTT', 'NTB', 'Gorontalo', 'Sulbar', 'Sulteng', 'Sulut', 'Sultra',
                            'Sulsel',
                            'Malut', 'Maluku', 'Papua Barat', 'Papua', 'Papua Tengah', 'Papua Pegunungan',
                            'Papua Selatan', 'Papua Barat Daya'
                        ],
                        data: [324, 398, 367, 342, 265, 334, 215, 249, 298, 198,
                            267, 345, 298, 245, 167, 378, 598, 734, 645, 312,
                            698, 398, 234, 267, 178, 215, 234, 298, 249, 434,
                            167, 198, 134, 156, 119, 104, 123, 131
                        ]
                    },
                    2025: {
                        labels: ['Aceh', 'Sumut', 'Sumsel', 'Sumbar', 'Bengkulu', 'Riau', 'Kep. Riau', 'Jambi',
                            'Lampung', 'Babel',
                            'Kalbar', 'Kaltim', 'Kalsel', 'Kalteng', 'Kaltara', 'Banten', 'DKI Jakarta',
                            'Jabar', 'Jateng', 'DIY',
                            'Jatim', 'Bali', 'NTT', 'NTB', 'Gorontalo', 'Sulbar', 'Sulteng', 'Sulut', 'Sultra',
                            'Sulsel',
                            'Malut', 'Maluku', 'Papua Barat', 'Papua', 'Papua Tengah', 'Papua Pegunungan',
                            'Papua Selatan', 'Papua Barat Daya'
                        ],
                        data: [156, 187, 165, 142, 98, 134, 89, 112, 124, 76,
                            112, 145, 124, 98, 67, 156, 287, 356, 298, 134,
                            324, 187, 98, 112, 78, 89, 98, 124, 106, 198,
                            72, 86, 54, 67, 48, 42, 51, 58
                        ]
                    }
                };

                // Function to generate alternating colors
                function generateAlternatingColors(dataLength) {
                    const colors = [{
                            bg: 'rgba(59, 130, 246, 0.8)',
                            border: 'rgba(59, 130, 246, 1)',
                            hover: 'rgba(59, 130, 246, 1)'
                        }, // Biru
                        {
                            bg: 'rgba(239, 68, 68, 0.8)',
                            border: 'rgba(239, 68, 68, 1)',
                            hover: 'rgba(239, 68, 68, 1)'
                        }, // Merah
                        {
                            bg: 'rgba(34, 197, 94, 0.8)',
                            border: 'rgba(34, 197, 94, 1)',
                            hover: 'rgba(34, 197, 94, 1)'
                        } // Hijau
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

                    return {
                        bgColors,
                        borderColors,
                        hoverColors
                    };
                }

                // Bar Chart: SDM per Provinsi
                const barCtx = document.getElementById('sdmBarChart').getContext('2d');
                console.log(barCtx);
                const initialColors = generateAlternatingColors(chartDataByYear[2025].data.length);

                let sdmBarChart = new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: chartDataByYear[2025].labels,
                        datasets: [{
                            label: 'Jumlah SDM',
                            data: chartDataByYear[2025].data,
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
                                    }
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

                // Year filter change handler
                document.getElementById('yearFilter').addEventListener('change', function(e) {
                    const year = e.target.value;
                    const newColors = generateAlternatingColors(chartDataByYear[year].data.length);

                    sdmBarChart.data.labels = chartDataByYear[year].labels;
                    sdmBarChart.data.datasets[0].data = chartDataByYear[year].data;
                    sdmBarChart.data.datasets[0].backgroundColor = newColors.bgColors;
                    sdmBarChart.data.datasets[0].borderColor = newColors.borderColors;
                    sdmBarChart.data.datasets[0].hoverBackgroundColor = newColors.hoverColors;
                    sdmBarChart.update();
                });


                // Pie Chart: Gender Distribution
                const genderCtx = document.getElementById('genderPieChart').getContext('2d');
                const genderPieChart = new Chart(genderCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Laki-laki', 'Perempuan'],
                        datasets: [{
                            data: [5432, 5782],
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
                                    label: function(context) {
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

                // Pie Chart: Module Distribution
                const moduleCtx = document.getElementById('modulePieChart').getContext('2d');
                const modulePieChart = new Chart(moduleCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Modul Makro', 'Modul Mikro', 'Modul Pembangunan Ketenagakerjaan'],
                        datasets: [{
                            data: [3456, 4234, 3524],
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(139, 92, 246, 0.8)'
                            ],
                            borderColor: [
                                'rgba(16, 185, 129, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(139, 92, 246, 1)'
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
                                    label: function(context) {
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
            });
        </script>
    @endpush

</x-dashboard::layouts.dashboard>
