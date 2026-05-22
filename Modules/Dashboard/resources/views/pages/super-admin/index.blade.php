<x-dashboard::layouts.dashboard title="Super Admin Dashboard">
    <div class="p-2 sm:p-6">
        <style>
            /* Custom thin scrollbar for modern dashboard */
            .custom-scrollbar::-webkit-scrollbar {
                width: 5px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background-color: #cbd5e1;
                border-radius: 10px;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background-color: #94a3b8;
            }
        </style>
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :items="[['label' => 'Dashboard']]" />


        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-5 mb-4 sm:mb-5 stats-grid">
            <!-- Total Admin Pusat -->
            <x-dashboard::superadmin.stats-card title="Total Admin Pusat" count="{{ $totalAdminPusat }}" growth="Aktif"
                period="Sistem">
                <div class="p-2 md:p-3 rounded-full gradient-indigo">
                    <i class="fas fa-users text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.stats-card>

            <!-- Total Admin Provinsi -->
            <x-dashboard::superadmin.stats-card title="Total Admin Provinsi" count="{{ $totalAdminProvinsi }}" growth="Aktif"
                period="Sistem">
                <div class="p-2 md:p-3 rounded-full gradient-emerald">
                    <i class="fas fa-user-check text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.stats-card>

            <!-- Total Admin Kab/Kota -->
            <x-dashboard::superadmin.stats-card title="Total Admin Kab/Kota" count="{{ $totalAdminKabKota }}" growth="Aktif"
                period="Sistem">
                <div class="p-2 md:p-3 rounded-full gradient-amber">
                    <i class="fas fa-user-slash text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.stats-card>

            <!-- Total Peserta E-Learning -->
            <x-dashboard::superadmin.stats-card title="Total Peserta" count="{{ $totalUser }}" growth="Aktif"
                period="Sistem">
                <div class="p-2 md:p-3 rounded-full gradient-rose">
                    <i class="fas fa-chart-line text-white text-base md:text-lg"></i>
                </div>
            </x-dashboard::superadmin.stats-card>
        </div>

        <!-- Main Dashboard Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 mb-4">
            <!-- Left Column: Chart & Activities -->
            <div class="lg:col-span-2 flex flex-col space-y-4 sm:space-y-6 h-full">
                <!-- User Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 md:p-6 transition-all hover:shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg md:text-xl font-bold text-gray-900 card-title">Distribusi Akun Pengguna</h2>
                            <p class="text-xs text-gray-500 mt-1">Perbandingan jumlah peran administrator dan peserta</p>
                        </div>
                        <span class="text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-100 px-3 py-1 rounded-full">
                            Total: {{ $totalAdminPusat + $totalAdminProvinsi + $totalAdminKabKota + $totalUser }} Akun
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-center">
                        <!-- Canvas Chart -->
                        <div class="md:col-span-2 relative h-48 flex justify-center items-center">
                            <canvas id="userDistributionChart" class="max-h-48"></canvas>
                        </div>
                        
                        <!-- Legend & Stats Detail -->
                        <div class="md:col-span-3 space-y-2">
                            <!-- Admin Pusat -->
                            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100 hover:bg-indigo-50/30 hover:border-indigo-100 transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-slate-700">Admin Pusat</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs sm:text-sm font-bold text-slate-900">{{ $totalAdminPusat }}</span>
                                    <span class="text-[10px] sm:text-xs text-slate-500 bg-white border border-slate-200 px-1.5 py-0.5 rounded ml-1 font-medium">
                                        {{ $totalAdminPusat + $totalAdminProvinsi + $totalAdminKabKota + $totalUser > 0 ? round(($totalAdminPusat / ($totalAdminPusat + $totalAdminProvinsi + $totalAdminKabKota + $totalUser)) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Admin Provinsi -->
                            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100 hover:bg-emerald-50/30 hover:border-emerald-100 transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-slate-700">Admin Provinsi</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs sm:text-sm font-bold text-slate-900">{{ $totalAdminProvinsi }}</span>
                                    <span class="text-[10px] sm:text-xs text-slate-500 bg-white border border-slate-200 px-1.5 py-0.5 rounded ml-1 font-medium">
                                        {{ $totalAdminPusat + $totalAdminProvinsi + $totalAdminKabKota + $totalUser > 0 ? round(($totalAdminProvinsi / ($totalAdminPusat + $totalAdminProvinsi + $totalAdminKabKota + $totalUser)) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Admin Kab/Kota -->
                            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100 hover:bg-amber-50/30 hover:border-amber-100 transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-slate-700">Admin Kab/Kota</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs sm:text-sm font-bold text-slate-900">{{ $totalAdminKabKota }}</span>
                                    <span class="text-[10px] sm:text-xs text-slate-500 bg-white border border-slate-200 px-1.5 py-0.5 rounded ml-1 font-medium">
                                        {{ $totalAdminPusat + $totalAdminProvinsi + $totalAdminKabKota + $totalUser > 0 ? round(($totalAdminKabKota / ($totalAdminPusat + $totalAdminProvinsi + $totalAdminKabKota + $totalUser)) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Peserta -->
                            <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100 hover:bg-rose-50/30 hover:border-rose-100 transition-all">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-slate-700">Peserta E-Learning</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs sm:text-sm font-bold text-slate-900">{{ $totalUser }}</span>
                                    <span class="text-[10px] sm:text-xs text-slate-500 bg-white border border-slate-200 px-1.5 py-0.5 rounded ml-1 font-medium">
                                        {{ $totalAdminPusat + $totalAdminProvinsi + $totalAdminKabKota + $totalUser > 0 ? round(($totalUser / ($totalAdminPusat + $totalAdminProvinsi + $totalAdminKabKota + $totalUser)) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Penting -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 md:p-6 transition-all hover:shadow-md flex-1 flex flex-col">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <div>
                            <h2 class="text-lg md:text-xl font-bold text-gray-900 card-title">Event Penting Terkini</h2>
                        </div>
                    </div>

                    <div class="flex-1 min-h-0 overflow-y-auto pr-1 space-y-3 md:space-y-4 max-h-[300px] lg:max-h-[245px] custom-scrollbar">
                        @forelse($recentActivities as $activity)
                            @php
                                $subjectName = $activity->subject_type ? class_basename($activity->subject_type) : 'Sistem';
                                $title = ucfirst($activity->description) . ' ' . $subjectName;
                                $description = $subjectName . ' (' . ($activity->subject?->name ?? $activity->subject_id) . ') telah ' . $activity->description;
                                $time = $activity->created_at ? $activity->created_at->diffForHumans(null, true) : '-';
                                $userName = $activity->causer?->name ?? 'System';
                            @endphp
                            <x-dashboard::superadmin.activity-log-item 
                                :title="$title"
                                :description="$description" 
                                :time="$time"
                                :user="$userName" />
                        @empty
                            <div class="p-6 text-center border border-dashed border-slate-300 rounded-lg text-slate-400 bg-slate-50/50">
                                <i class="fas fa-history text-xl mb-2 text-slate-300"></i>
                                <p class="text-sm">Belum ada log aktivitas admin saat ini.</p>
                            </div>
                        @endforelse
                    </div>

                    @if(count($recentActivities) > 0)
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <p class="text-sm text-gray-500">Menampilkan {{ count($recentActivities) }} event log terbaru</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar / Right Column -->
            <div class="space-y-4 sm:space-y-6 flex flex-col justify-between h-full">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-4 md:mb-6 card-title">Aksi Cepat</h2>

                    <div class="grid grid-cols-2 gap-3 md:gap-4 quick-actions">
                        <a href="{{ route('super-admin.user-management.create') }}"
                            class="bg-indigo-50 border border-indigo-100 rounded-xl p-3 md:p-4 flex flex-col items-center justify-center text-center hover:bg-indigo-100 transition-colors card-hover">
                            <div class="p-2 md:p-3 rounded-full bg-indigo-100 text-indigo-600 mb-2 md:mb-3">
                                <i class="fas fa-user-plus text-base md:text-lg"></i>
                            </div>
                            <p class="font-medium text-gray-900 text-sm md:text-base">Tambah User</p>
                            <p class="text-xs text-gray-500 mt-1">Buat akun baru</p>
                        </a>

                        <a href="{{ route('super-admin.lembaga.index') }}"
                            class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 md:p-4 flex flex-col items-center justify-center text-center hover:bg-emerald-100 transition-colors card-hover">
                            <div class="p-2 md:p-3 rounded-full bg-emerald-100 text-emerald-600 mb-2 md:mb-3">
                                <i class="fas fa-building text-base md:text-lg"></i>
                            </div>
                            <p class="font-medium text-gray-900 text-sm md:text-base">Kelola Lembaga</p>
                            <p class="text-xs text-gray-500 mt-1">Kementerian/Pusat</p>
                        </a>

                        <a href="{{ route('super-admin.instansi.index') }}"
                            class="bg-amber-50 border border-amber-100 rounded-xl p-3 md:p-4 flex flex-col items-center justify-center text-center hover:bg-amber-100 transition-colors card-hover">
                            <div class="p-2 md:p-3 rounded-full bg-amber-100 text-amber-600 mb-2 md:mb-3">
                                <i class="fas fa-map-marked-alt text-base md:text-lg"></i>
                            </div>
                            <p class="font-medium text-gray-900 text-sm md:text-base">Kelola Instansi</p>
                            <p class="text-xs text-gray-500 mt-1">Dinas Daerah</p>
                        </a>

                        <a href="{{ route('super-admin.roles.index') }}"
                            class="bg-purple-50 border border-purple-100 rounded-xl p-3 md:p-4 flex flex-col items-center justify-center text-center hover:bg-purple-100 transition-colors card-hover">
                            <div class="p-2 md:p-3 rounded-full bg-purple-100 text-purple-600 mb-2 md:mb-3">
                                <i class="fas fa-user-shield text-base md:text-lg"></i>
                            </div>
                            <p class="font-medium text-gray-900 text-sm md:text-base">Role & Permission</p>
                            <p class="text-xs text-gray-500 mt-1">Hak Akses</p>
                        </a>
                    </div>
                </div>

                <!-- Ringkasan Sistem -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-4 md:mb-6 card-title">Ringkasan Sistem</h2>
                    
                    <div class="space-y-4">
                        <!-- Total Lembaga -->
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100/80">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <i class="fas fa-building text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Lembaga Pusat</p>
                                    <p class="text-sm font-semibold text-gray-900">Kementerian / Lembaga</p>
                                </div>
                            </div>
                            <span class="text-base font-bold text-gray-900 bg-white border border-slate-200 px-3 py-1 rounded-lg shadow-sm">
                                {{ $totalLembaga }}
                            </span>
                        </div>

                        <!-- Total Instansi -->
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100/80">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <i class="fas fa-map-marked-alt text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Instansi Daerah</p>
                                    <p class="text-sm font-semibold text-gray-900">Dinas Provinsi / Kab / Kota</p>
                                </div>
                            </div>
                            <span class="text-base font-bold text-gray-900 bg-white border border-slate-200 px-3 py-1 rounded-lg shadow-sm">
                                {{ $totalInstansi }}
                            </span>
                        </div>

                        <!-- Total Kursus -->
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100/80">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Total Kursus</p>
                                    <p class="text-sm font-semibold text-gray-900">Program Pembelajaran LMS</p>
                                </div>
                            </div>
                            <span class="text-base font-bold text-gray-900 bg-white border border-slate-200 px-3 py-1 rounded-lg shadow-sm">
                                {{ $totalCourses }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('userDistributionChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Admin Pusat', 'Admin Provinsi', 'Admin Kab/Kota', 'Peserta E-Learning'],
                        datasets: [{
                            data: [
                                {{ $totalAdminPusat }},
                                {{ $totalAdminProvinsi }},
                                {{ $totalAdminKabKota }},
                                {{ $totalUser }}
                            ],
                            backgroundColor: [
                                '#6366f1', // Indigo
                                '#10b981', // Emerald
                                '#f59e0b', // Amber
                                '#f43f5e'  // Rose
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 4
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
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.raw !== undefined) {
                                            label += context.raw + ' akun';
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            });
        </script>
    @endpush
</x-dashboard::layouts.dashboard>
