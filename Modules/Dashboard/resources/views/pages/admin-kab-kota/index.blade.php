<x-dashboard::layouts.dashboard title="Dashboard Admin Kab/Kota">
    <!-- Wrapper Utama -->
    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto space-y-6 sm:space-y-8 bg-slate-50/50 min-h-screen">

        <!-- Header & Breadcrumb -->
        <div class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Halo, {{ $user->name }}</h1>
                    <p class="text-sm text-slate-500 font-medium mt-1">
                        Masuk sebagai <span
                            class="text-[#13416B] font-bold">{{ $user->getRoleNames()->implode(', ') }}</span> — Wilayah
                        {{ $user->scopeArea?->regency?->name ?? 'Belum Ditetapkan' }}
                    </p>
                </div>
                
            </div>

            @if (!$user->hasCompleteScope())
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 sm:p-5 shadow-sm flex items-start gap-4">
                    <div
                        class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-amber-800 mb-1">Wilayah Kab/Kota Belum Ditetapkan</h2>
                        <p class="text-sm text-amber-700 leading-relaxed">
                            Akun ini belum memiliki penetapan wilayah Kabupaten/Kota pada sistem. Silakan hubungi Admin
                            Pusat untuk konfigurasi wilayah.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        @php
            $totalSdmPeriode = array_sum($sdmPerTahun);
            $totalModul = count($courses);
        @endphp

      

        <!-- ========================================================= -->
        <!-- 2. BLOK PERENCANAAN STRATEGIS (RTK & PROJECT)             -->
        <!-- ========================================================= -->
      

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informasi RTK Aktif -->
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden relative">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center bg-[#13416B] text-white rounded-xl shadow-sm">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Informasi RTK Saat Ini</h2>
                        <p class="text-[11px] text-slate-500">Rencana Tenaga Kerja Daerah Kab/Kota</p>
                    </div>
                </div>
                <div class="p-6">
                    @if ($rtkActive)
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Nama Dokumen</p>
                                <p class="text-sm font-bold text-slate-800">{{ $rtkActive->name }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">Periode</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $rtkActive->start_date }} -
                                        {{ $rtkActive->end_date }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 mb-1">Sisa Waktu</p>
                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ max(0, (int) $rtkActive->end_date - date('Y')) }} Tahun</p>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-slate-100 flex gap-2">
                                <span
                                    class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-md text-xs font-semibold">
                                    {{ $rtkActive->status_verification->label() ?? 'Menunggu' }}
                                </span>
                                <span
                                    class="px-3 py-1 bg-slate-50 text-slate-700 border border-slate-200 rounded-md text-xs font-semibold">
                                    {{ $rtkActive->status_document->label() ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center text-center h-32">
                            <i class="fas fa-folder-open text-3xl text-slate-300 mb-2"></i>
                            <p class="text-sm font-medium text-slate-600">Belum Ada RTK Aktif</p>
                            <p class="text-xs text-slate-400">Silakan ajukan dokumen RTK wilayah Anda.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Proyek -->
           
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden relative flex flex-col h-full">
                <!-- Header (Dibuat shrink-0 agar tidak tertekan) -->
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-[#13416B] text-white rounded-xl shadow-sm">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Ringkasan Proyek</h2>
                            <p class="text-[11px] text-slate-500">Pemantauan kegiatan pelatihan/proyek</p>
                        </div>
                    </div>
                    <a href="{{ route('admin-kab-kota.project.index') }}"
                        class="text-xs font-bold text-[#13416B] hover:underline">Kelola Proyek <i
                            class="fas fa-arrow-right ml-1"></i></a>
                </div>
                
                <!-- Konten (flex-1 agar memenuhi ruang, items-center & justify-center agar tepat di tengah vertikal) -->
                <div class="p-6 flex flex-col items-center justify-center flex-1">
                    @if ($totalProjects > 0)
                        <!-- max-w-md dan mx-auto memastikan konten tidak melebar tanpa arah -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-8 w-full max-w-md mx-auto">
                            
                            <!-- Ukuran SVG Responsif: HP = w-24 (96px), PC = w-28 (112px) -->
                            <div class="relative w-24 h-24 sm:w-28 sm:h-28 flex items-center justify-center shrink-0">
                                <!-- ViewBox dinormalkan ke 100x100 agar perhitungannya mudah -->
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="40" stroke="#f1f5f9" stroke-width="10" fill="none" />
                                    <!-- Keliling 2 * pi * 40 = 251.2 -->
                                    <circle cx="50" cy="50" r="40" stroke="#f59e0b" stroke-width="10"
                                        fill="none" stroke-linecap="round"
                                        stroke-dasharray="{{ ($onProgressProjects / $totalProjects) * 251.2 }} 251.2" />
                                </svg>
                                <div class="absolute text-center">
                                    <span class="text-xl sm:text-2xl font-extrabold text-slate-800">{{ round(($onProgressProjects / $totalProjects) * 100) }}%</span>
                                </div>
                            </div>
                            
                            <!-- Teks dipusatkan di HP (text-center) dan rata kiri di PC (sm:text-left) -->
                            <div class="text-center sm:text-left">
                                <p class="text-sm sm:text-base font-bold text-slate-700 mb-1">Sedang Berjalan</p>
                                <p class="text-3xl font-extrabold text-amber-500 mb-2">{{ $onProgressProjects }} <span
                                        class="text-sm font-semibold text-slate-400">dari {{ $totalProjects }} Proyek</span></p>
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">Pastikan seluruh proyek berjalan sesuai dengan rentang waktu yang ditentukan.</p>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center text-center">
                            <i class="fas fa-clipboard-list text-4xl text-slate-300 mb-3"></i>
                            <p class="text-base font-medium text-slate-600">Belum Ada Proyek</p>
                            <p class="text-sm text-slate-400 mt-1">Data pendelegasian proyek wilayah ini masih kosong.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 3. BLOK KAPASITAS SDM & E-LEARNING                        -->
        <!-- ========================================================= -->
      

        <!-- GRAFIK TREN PARTISIPASI SDM -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div
                class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center bg-[#13416B] text-white rounded-xl shadow-sm">
                        <i class="fas fa-chart-line text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Tren Pendaftaran E-Learning</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Statistik pertumbuhan peserta dalam blok 5
                            tahun terakhir</p>
                    </div>
                </div>

                <div class="w-full sm:w-auto sm:max-w-xs shrink-0 flex items-center gap-2">
                    <select id="yearFilter" onchange="filterDataYear(this.value)"
                        class="w-full text-sm border-slate-200 rounded-lg focus:ring-[#13416B] focus:border-[#13416B] cursor-pointer bg-slate-50">
                        @foreach ($years as $y)
                            <option value="{{ $y }}"
                                {{ (string) $y === (string) $selectedYear ? 'selected' : '' }}>Tahun Berakhir
                                {{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="p-5 sm:p-6">
                <div id="sdmTrendChartContainer"
                    class="relative h-64 sm:h-72 w-full {{ $totalSdmPeriode > 0 ? '' : 'hidden' }}">
                    <canvas id="sdmTrendBarChart"></canvas>
                </div>

                <div id="sdmTrendEmptyState"
                    class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4 {{ $totalSdmPeriode > 0 ? 'hidden' : '' }}">
                    <div
                        class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                        <i class="fas fa-chart-area text-xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Belum ada data pendaftar</p>
                    <p class="text-xs text-slate-500 mt-1">Tidak ada catatan SDM pada periode 5 tahun ini.</p>
                </div>
            </div>
        </div>

        <!-- DISTRIBUSI MODUL & GENDER -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div
                    class="w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center bg-[#13416B] text-white rounded-xl shadow-sm">
                    <i class="fas fa-medal text-lg"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Modul Terpopuler & Demografi</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">Peringkat kursus dan sebaran gender (Kuning &
                        Biru)</p>
                </div>
            </div>

            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Leaderboard Modul (2 Kolom Kiri) -->
                <div class="lg:col-span-2 border-b lg:border-b-0 lg:border-r border-slate-100 pb-6 lg:pb-0 lg:pr-8">
                    <h4 class="text-sm font-bold text-slate-700 mb-5 uppercase tracking-wider"><i
                            class="fas fa-list-ol text-slate-400 mr-2"></i> Peringkat Modul Pelatihan</h4>

                    <div id="courseListContainer"
                        class="space-y-5 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar {{ count($courses) > 0 ? '' : 'hidden' }}">
                        <!-- Dirender via Javascript -->
                    </div>

                    <div id="courseEmptyState"
                        class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4 {{ count($courses) > 0 ? 'hidden' : '' }}">
                        <div
                            class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Belum Ada Modul Diambil</p>
                    </div>
                </div>

                <!-- Pie Chart Gender (1 Kolom Kanan) - WARNA KUNING & BIRU -->
                <div class="lg:col-span-1 flex flex-col justify-center">
                    <h4 class="text-sm font-bold text-center text-slate-700 mb-4 uppercase tracking-wider">Distribusi
                        Gender</h4>
                    @if ($genderMale == 0 && $genderFemale == 0)
                        <div
                            class="h-48 flex items-center justify-center text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                            <span class="text-xs font-medium">Data gender kosong</span>
                        </div>
                    @else
                        <div class="relative h-56 w-full flex items-center justify-center">
                            <canvas id="genderPieChart"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Reload halaman dengan parameter year
                window.filterDataYear = function(year) {
                    window.location.href = `{{ route('admin-kab-kota.dashboard') }}?year=${year}`;
                };

                // 1. GRAFIK TREN SDM (BAR CHART)
                @if ($totalSdmPeriode > 0)
                    const sdmTrendCtx = document.getElementById('sdmTrendBarChart').getContext('2d');
                    const sdmTrendRaw = @json($sdmPerTahun);
                    const sdmLabels = Object.keys(sdmTrendRaw);
                    const sdmData = Object.values(sdmTrendRaw);

                    new Chart(sdmTrendCtx, {
                        type: 'bar',
                        data: {
                            labels: sdmLabels,
                            datasets: [{
                                label: 'Jumlah Pendaftar',
                                data: sdmData,
                                backgroundColor: 'rgba(19, 65, 107, 0.85)', // Biru
                                borderColor: 'rgba(19, 65, 107, 1)',
                                borderWidth: 1,
                                borderRadius: 6,
                                barPercentage: 0.5
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
                                    backgroundColor: 'rgba(19, 65, 107, 0.95)',
                                    padding: 12,
                                    cornerRadius: 6,
                                    titleFont: {
                                        size: 13,
                                        weight: 'bold'
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#f1f5f9'
                                    },
                                    ticks: {
                                        font: {
                                            size: 11
                                        },
                                        stepSize: 1
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 12,
                                            weight: 'bold'
                                        }
                                    }
                                }
                            }
                        }
                    });
                @endif

                // 2. LEADERBOARD MODUL
                const coursesRaw = @json($courses);

                function renderCourseLeaderboard(dataObj) {
                    const container = document.getElementById('courseListContainer');
                    const emptyState = document.getElementById('courseEmptyState');

                    const keys = Object.keys(dataObj);
                    if (keys.length === 0) {
                        container.classList.add('hidden');
                        emptyState.classList.remove('hidden');
                        return;
                    }

                    container.classList.remove('hidden');
                    emptyState.classList.add('hidden');

                    const coursesArray = keys.map(k => ({
                        name: k,
                        total: dataObj[k]
                    }));
                    coursesArray.sort((a, b) => b.total - a.total);

                    let maxTotal = coursesArray[0].total || 1;
                    let htmlContent = '';

                    coursesArray.forEach((item, index) => {
                        const percentage = (item.total / maxTotal) * 100;
                        htmlContent += `
                            <div class="flex items-center gap-3.5 group">
                                <div class="w-6 text-sm font-bold text-slate-400 text-right shrink-0 group-hover:text-[#13416B] transition-colors">${index + 1}.</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-sm font-semibold text-slate-700 truncate pr-2 group-hover:text-[#13416B] transition-colors">${item.name}</span>
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
                renderCourseLeaderboard(coursesRaw);

                // 3. PIE CHART GENDER (Warna KUNING dan BIRU)
                if (document.getElementById('genderPieChart')) {
                    const genderCtx = document.getElementById('genderPieChart').getContext('2d');
                    new Chart(genderCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Laki-laki', 'Perempuan'],
                            datasets: [{
                                data: [{{ $genderMale }}, {{ $genderFemale }}],
                                // MALE: Biru Sirenata | FEMALE: Kuning (Amber-500)
                                backgroundColor: ['rgba(19, 65, 107, 0.85)',
                                    'rgba(245, 158, 11, 0.85)'
                                ],
                                borderColor: ['#ffffff', '#ffffff'],
                                borderWidth: 2,
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
                                    labels: {
                                        boxWidth: 10,
                                        font: {
                                            size: 11
                                        },
                                        padding: 15
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(19, 65, 107, 0.95)',
                                    padding: 10,
                                    cornerRadius: 6,
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = total > 0 ? ((context.parsed / total) * 100)
                                                .toFixed(1) : 0;
                                            return ` ${context.label}: ${context.parsed} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-dashboard::layouts.dashboard>
