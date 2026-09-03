<x-dashboard::layouts.dashboard title="Dashboard Admin Kab/Kota">
    <!-- Wrapper Utama Senada dengan Admin Pusat & Provinsi -->
    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto space-y-6 sm:space-y-8 bg-slate-50/50 min-h-screen">
        
        <!-- Header & Breadcrumb -->
        <div class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Halo, {{ $user->name }}</h1>
                    <p class="text-sm text-slate-500 font-medium mt-1">
                        Masuk sebagai <span class="text-[#13416B] font-bold">{{ $user->getRoleNames()->implode(', ') }}</span> — Wilayah {{ $user->scopeArea?->regency?->name ?? 'Belum Ditetapkan' }}
                    </p>
                </div>
                <x-breadcrumb :items="[['label' => 'Dashboard']]" />
            </div>

            <!-- Peringatan Wilayah Belum Ditetapkan -->
            @if (!$user->hasCompleteScope())
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 sm:p-5 shadow-sm flex items-start gap-4 transition-all">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-amber-800 mb-1">Wilayah Kab/Kota Belum Ditetapkan</h2>
                        <p class="text-sm text-amber-700 leading-relaxed">
                            Akun ini belum memiliki penetapan wilayah Kabupaten/Kota pada sistem.
                            Untuk melanjutkan pengelolaan data, silakan hubungi Admin Pusat agar wilayah dapat dikonfigurasi terlebih dahulu.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        @php
            // Hitung total partisipasi dari array 5 tahun terakhir
            $totalSdmPeriode = array_sum($sdmPerTahun);
            $totalModul = count($courses);
        @endphp

        <!-- ===================================== -->
        <!-- 1. STATS GRID (Full Sirenata Theme)   -->
        <!-- ===================================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            <!-- Total Partisipasi SDM -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">Peserta Terdaftar</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $totalSdmPeriode }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Periode 5 tahun terpilih</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#13416B] text-white flex items-center justify-center shrink-0 shadow-sm border border-[#0f3354]">
                    <i class="fas fa-users text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- Total Laki-laki -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">Peserta Pria</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $genderMale }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Total keseluruhan sistem</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#13416B]/80 text-white flex items-center justify-center shrink-0 shadow-sm border border-[#0f3354]">
                    <i class="fas fa-male text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- Total Perempuan -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">Peserta Wanita</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $genderFemale }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Total keseluruhan sistem</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#184A78] text-white flex items-center justify-center shrink-0 shadow-sm border border-[#0f3354]">
                    <i class="fas fa-female text-xl sm:text-2xl"></i>
                </div>
            </div>

            <!-- Modul Aktif -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">Modul Diikuti</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $totalModul }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Jenis kursus terdaftar</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#0f3354] text-white flex items-center justify-center shrink-0 shadow-sm border border-slate-800">
                    <i class="fas fa-book-open text-xl sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 2. GRAFIK TREN PARTISIPASI SDM (BAR CHART)                -->
        <!-- ========================================================= -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center bg-[#13416B] text-white rounded-xl shrink-0 shadow-sm border border-[#0f3354]">
                        <i class="fas fa-chart-line text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Tren Pendaftaran E-Learning</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Statistik pertumbuhan peserta dalam 5 tahun terakhir berdasarkan batas tahun</p>
                    </div>
                </div>

                <!-- Dropdown Filter Tahun -->
                <div class="w-full sm:w-auto sm:max-w-xs shrink-0 flex items-center gap-2">
                    <select id="yearFilter" onchange="filterDataYear(this.value)" class="w-full text-sm border-slate-200 rounded-lg focus:ring-[#13416B] focus:border-[#13416B] text-ellipsis overflow-hidden cursor-pointer bg-slate-50">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ (string)$y === (string)$selectedYear ? 'selected' : '' }}>Tahun Berakhir {{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="p-5 sm:p-6">
                <!-- CHART CONTAINER -->
                <div id="sdmTrendChartContainer" class="relative h-72 sm:h-[400px] w-full {{ $totalSdmPeriode > 0 ? '' : 'hidden' }}">
                    <canvas id="sdmTrendBarChart"></canvas>
                </div>
                
                <!-- EMPTY STATE -->
                <div id="sdmTrendEmptyState" class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4 {{ $totalSdmPeriode > 0 ? 'hidden' : '' }}">
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                        <i class="fas fa-chart-area text-xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Belum ada data pendaftar</p>
                    <p class="text-xs text-slate-500 mt-1">Tidak ada catatan SDM yang mengikuti e-learning pada periode 5 tahun ini.</p>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 3. DISTRIBUSI MODUL & GENDER                              -->
        <!-- ========================================================= -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center bg-[#13416B] text-white rounded-xl shrink-0 shadow-sm border border-[#0f3354]">
                    <i class="fas fa-medal text-lg"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800">Modul Terpopuler & Demografi</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">Peringkat kursus yang paling banyak diikuti dan sebaran gender</p>
                </div>
            </div>
            
            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                
                <!-- Leaderboard Modul (2 Kolom Kiri) -->
                <div class="lg:col-span-2 border-b lg:border-b-0 lg:border-r border-slate-100 pb-6 lg:pb-0 lg:pr-8">
                    <h4 class="text-sm font-bold text-slate-700 mb-5 uppercase tracking-wider"><i class="fas fa-list-ol text-slate-400 mr-2"></i> Peringkat Modul Pelatihan</h4>
                    
                    <!-- LEADERBOARD CONTAINER -->
                    <div id="courseListContainer" class="space-y-5 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar {{ count($courses) > 0 ? '' : 'hidden' }}">
                        <!-- Dirender via Javascript -->
                    </div>
                    
                    <div id="courseEmptyState" class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4 {{ count($courses) > 0 ? 'hidden' : '' }}">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Belum Ada Modul Diambil</p>
                        <p class="text-xs text-slate-500 mt-1">Belum ada pengguna yang mendaftar pada modul apapun.</p>
                    </div>
                </div>

                <!-- Pie Chart Gender (1 Kolom Kanan) -->
                <div class="lg:col-span-1 flex flex-col justify-center">
                    <h4 class="text-sm font-bold text-center text-slate-700 mb-4 uppercase tracking-wider">Distribusi Gender</h4>
                    @if ($genderMale == 0 && $genderFemale == 0)
                        <div class="h-48 flex items-center justify-center text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-200">
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
            document.addEventListener('DOMContentLoaded', function () {

                // Reload halaman dengan parameter year (AJAX fallback karena Controller saat ini merender view utuh)
                window.filterDataYear = function(year) {
                    window.location.href = `{{ route('admin-kab-kota.dashboard') }}?year=${year}`;
                };

                // =========================================================
                // 1. GRAFIK TREN SDM (BAR CHART)
                // =========================================================
                @if($totalSdmPeriode > 0)
                    const sdmTrendCtx = document.getElementById('sdmTrendBarChart').getContext('2d');
                    
                    const sdmTrendRaw = @json($sdmPerTahun);
                    const sdmLabels = Object.keys(sdmTrendRaw);
                    const sdmData = Object.values(sdmTrendRaw);

                    window.sdmTrendChartInstance = new Chart(sdmTrendCtx, {
                        type: 'bar',
                        data: {
                            labels: sdmLabels,
                            datasets: [{
                                label: 'Jumlah Pendaftar',
                                data: sdmData,
                                backgroundColor: 'rgba(19, 65, 107, 0.85)', // Biru Sirenata
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
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(19, 65, 107, 0.95)',
                                    padding: 12,
                                    cornerRadius: 6,
                                    titleFont: { size: 13, weight: 'bold' }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: '#f1f5f9' },
                                    ticks: { font: { size: 11 }, stepSize: 1 }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 12, weight: 'bold' } }
                                }
                            }
                        }
                    });
                @endif

                // =========================================================
                // 2. FUNGSI RENDER LEADERBOARD MODUL TERPOPULER
                // =========================================================
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

                    // Convert to array of objects
                    const coursesArray = keys.map(k => ({
                        name: k,
                        total: dataObj[k]
                    }));

                    // Urutkan dari terbanyak ke sedikit
                    coursesArray.sort((a, b) => b.total - a.total);

                    // Cari max value untuk persentase bar
                    let maxTotal = coursesArray[0].total;
                    if (maxTotal === 0) maxTotal = 1;

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

                // Render saat load
                renderCourseLeaderboard(coursesRaw);

                // =========================================================
                // 3. PIE CHART GENDER
                // =========================================================
                if (document.getElementById('genderPieChart')) {
                    const genderCtx = document.getElementById('genderPieChart').getContext('2d');
                    new Chart(genderCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Laki-laki', 'Perempuan'],
                            datasets: [{
                                data: [{{ $genderMale }}, {{ $genderFemale }}],
                                backgroundColor: ['rgba(19, 65, 107, 0.85)', 'rgba(236, 72, 153, 0.85)'], // Biru Sirenata & Pink
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
                                    labels: { boxWidth: 10, font: { size: 11 }, padding: 15 }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(19, 65, 107, 0.95)',
                                    padding: 10,
                                    cornerRadius: 6,
                                    callbacks: {
                                        label: function (context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
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