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
            <!-- Total Admin Pusat (dari DB) -->
            <x-dashboard::superadmin.statscard title="Total Admin Pusat" :count="$totalAdminPusat" growth="Data Aktual"
                period="Dari Database">
                <div class="p-2 md:p-3 rounded-full gradient-indigo">
                    <i class="fas fa-users text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.statscard>

            <!-- Admin Aktif (Admin Provinsi + Admin Kab/Kota) -->
            <x-dashboard::superadmin.statscard title="Admin Aktif" :count="$adminAktif" growth="Data Aktual"
                period="Admin Regional">
                <div class="p-2 md:p-3 rounded-full gradient-emerald">
                    <i class="fas fa-user-check text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.statscard>

            <!-- Admin Nonaktif (Belum Tersedia) -->
            <x-dashboard::superadmin.statscard title="Admin Nonaktif" count="Belum Tersedia" growth="-"
                period="Fitur belum tersedia">
                <div class="p-2 md:p-3 rounded-full gradient-amber">
                    <i class="fas fa-user-slash text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.statscard>

            <!-- Aktivitas Admin (dari DB) -->
            <x-dashboard::superadmin.statscard title="Aktivitas Admin" :count="$aktivitasAdmin" growth="Data Aktual"
                period="Bulan ini">
                <div class="p-2 md:p-3 rounded-full gradient-rose">
                    <i class="fas fa-chart-line text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.statscard>
        </div>

        <!-- Bar Chart: SDM per Provinsi (dari DB) -->
        <div class="bg-white rounded-lg p-3 sm:p-6 shadow-sm mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-0 mb-4 sm:mb-6">
                <h2 class="text-base sm:text-xl font-bold text-gray-900">Jumlah SDM (User) per Provinsi</h2>
            </div>
            @if($sdmPerProvinsi->count() > 0)
                <div class="h-64 sm:h-96">
                    <canvas id="sdmBarChart"></canvas>
                </div>
            @else
                <div class="h-64 sm:h-96 flex items-center justify-center">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-chart-bar text-5xl text-gray-300"></i>
                        </div>
                        <p class="text-lg font-semibold text-gray-400">Belum Ada Data</p>
                        <p class="text-sm text-gray-400 mt-1">Belum ada user dengan data provinsi</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bar Chart: Masa Aktif RTK per Provinsi -->
        <div class="bg-white rounded-lg p-4 md:p-6 shadow-sm mb-4 md:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 md:mb-6">
                <h2 class="text-lg md:text-xl font-bold text-gray-900">Masa Aktif RTK per Provinsi</h2>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Tahun Sekarang:</span> {{ date('Y') }}
                    <span class="mx-2">|</span>
                    <span class="font-medium">Masa Berlaku:</span> 5 Tahun
                </div>
            </div>
            <div class="h-64 flex flex-col justify-center items-center text-gray-500">
                <i class="fas fa-link-slash text-4xl mb-3 text-gray-300"></i>
                <p class="font-medium">Belum Dihubungkan</p>
                <p class="text-xs mt-1 text-center">Data Masa Aktif RTK belum tersedia / belum dihubungkan dengan
                    database</p>
            </div>
        </div>

        <!-- Pie Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Card 1: RTK Validity Status Pie Chart -->
            <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Status Masa Berlaku RTK ({{ date('Y') }})
                </h2>
                <div class="h-60 sm:h-80 flex flex-col justify-center items-center text-gray-500">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-link-slash text-5xl text-gray-300"></i>
                        </div>
                        <p class="text-lg font-semibold text-gray-400">Belum Dihubungkan</p>
                        <p class="text-sm text-gray-400 mt-1">Data RTK belum tersedia</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Gender Distribution Pie Chart (dari DB) -->
            <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Perbandingan Jenis Kelamin
                </h2>
                @if($genderMale + $genderFemale > 0)
                    <div class="h-60 sm:h-80 flex items-center justify-center">
                        <canvas id="genderPieChart"></canvas>
                    </div>
                @else
                    <div class="h-60 sm:h-80 flex items-center justify-center">
                        <div class="text-center">
                            <div class="mb-4">
                                <i class="fas fa-venus-mars text-5xl text-gray-300"></i>
                            </div>
                            <p class="text-lg font-semibold text-gray-400">Belum Ada Data</p>
                            <p class="text-sm text-gray-400 mt-1">Data jenis kelamin user belum diisi di profil</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Card 3: Module Distribution Pie Chart (Belum Tersedia) -->
            <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Perbandingan Modul yang
                    Diambil</h2>
                <div class="h-60 sm:h-80 flex items-center justify-center">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-book text-5xl text-gray-300"></i>
                        </div>
                        <p class="text-lg font-semibold text-gray-400">Belum Tersedia</p>
                        <p class="text-sm text-gray-400 mt-1">Data modul dan enrollment belum tersedia di database</p>
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

                    // Function to generate alternating colors
                    function generateAlternatingColors(dataLength) {
                        const colors = [
                            { bg: 'rgba(59, 130, 246, 0.8)', border: 'rgba(59, 130, 246, 1)', hover: 'rgba(59, 130, 246, 1)' },
                            { bg: 'rgba(239, 68, 68, 0.8)', border: 'rgba(239, 68, 68, 1)', hover: 'rgba(239, 68, 68, 1)' },
                            { bg: 'rgba(34, 197, 94, 0.8)', border: 'rgba(34, 197, 94, 1)', hover: 'rgba(34, 197, 94, 1)' }
                        ];

                        const bgColors = [], borderColors = [], hoverColors = [];
                        for (let i = 0; i < dataLength; i++) {
                            const colorIndex = i % 3;
                            bgColors.push(colors[colorIndex].bg);
                            borderColors.push(colors[colorIndex].border);
                            hoverColors.push(colors[colorIndex].hover);
                        }
                        return { bgColors, borderColors, hoverColors };
                    }

                    const sdmLabels = @json($sdmPerProvinsi->pluck('province_name'));
                    const sdmData = @json($sdmPerProvinsi->pluck('total'));
                    const barColors = generateAlternatingColors(sdmData.length);

                    new Chart(barCtx, {
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
                });
        </script>
    @endpush

</x-dashboard::layouts.dashboard>