<x-dashboard::layouts.dashboard title="Super Admin Dashboard">
    <div class="p-2 sm:p-6">
        <div class="mb-5">
            <h1 class="text-2xl font-bold">Halo {{ $user->name }}</h1>

            <p class="text-sm text-gray-500">Login Sebagai {{ $user->getRoleNames()->implode(', ') }}</p>
        </div>
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('super-admin.dashboard') }}"
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

        <!-- Main Dashboard Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
            <!-- Event Penting -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 md:p-6 h-full">
                    <div class="flex items-center justify-between mb-4 md:mb-6">
                        <div>
                            <h2 class="text-lg md:text-xl font-bold text-gray-900 card-title">Event Penting
                                Terkini
                            </h2>
                        </div>
                        <a href="#" class="text-sm text-indigo-600 hover:underline font-medium">Lihat
                            Semua</a>
                    </div>

                    <div class="space-y-3 md:space-y-4">
                        <x-dashboard::superadmin.activitylogitem title="Update Konfigurasi Integrasi"
                            description="Konfigurasi API Key KCLC diperbarui oleh Superadmin." time="10m"
                            user="Superadmin" />
                        <x-dashboard::superadmin.activitylogitem title="Update Konfigurasi Integrasi"
                            description="Konfigurasi API Key KCLC diperbarui oleh Superadmin." time="10m"
                            user="Superadmin" />
                        <x-dashboard::superadmin.activitylogitem title="Update Konfigurasi Integrasi"
                            description="Konfigurasi API Key KCLC diperbarui oleh Superadmin." time="10m"
                            user="Superadmin" />
                        <x-dashboard::superadmin.activitylogitem title="Update Konfigurasi Integrasi"
                            description="Konfigurasi API Key KCLC diperbarui oleh Superadmin." time="10m"
                            user="Superadmin" />

                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <p class="text-sm text-gray-500">Total event hari ini: <span
                                    class="font-medium text-gray-900">8 event</span></p>
                            <button
                                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium px-4 py-2 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors w-full sm:w-auto text-center">
                                <i class="fas fa-history mr-2"></i> Muat Ulang Event
                            </button>
                        </div>
                    </div>
                </div>
            </div>

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

                    <button onclick="exportData()"
                        class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 md:p-4 flex flex-col items-center justify-center text-center hover:bg-emerald-100 transition-colors card-hover">
                        <div class="p-2 md:p-3 rounded-full bg-emerald-100 text-emerald-600 mb-2 md:mb-3">
                            <i class="fas fa-file-export text-base md:text-lg"></i>
                        </div>
                        <p class="font-medium text-gray-900 text-sm md:text-base">Ekspor Data</p>
                        <p class="text-xs text-gray-500 mt-1">Laporan admin</p>
                    </button>

                    <button onclick="openSettings()"
                        class="bg-amber-50 border border-amber-100 rounded-xl p-3 md:p-4 flex flex-col items-center justify-center text-center hover:bg-amber-100 transition-colors card-hover">
                        <div class="p-2 md:p-3 rounded-full bg-amber-100 text-amber-600 mb-2 md:mb-3">
                            <i class="fas fa-cog text-base md:text-lg"></i>
                        </div>
                        <p class="font-medium text-gray-900 text-sm md:text-base">Pengaturan</p>
                        <p class="text-xs text-gray-500 mt-1">Konfigurasi sistem</p>
                    </button>

                    <button onclick="viewActivityLogs()"
                        class="bg-purple-50 border border-purple-100 rounded-xl p-3 md:p-4 flex flex-col items-center justify-center text-center hover:bg-purple-100 transition-colors card-hover">
                        <div class="p-2 md:p-3 rounded-full bg-purple-100 text-purple-600 mb-2 md:mb-3">
                            <i class="fas fa-history text-base md:text-lg"></i>
                        </div>
                        <p class="font-medium text-gray-900 text-sm md:text-base">Log Aktivitas</p>
                        <p class="text-xs text-gray-500 mt-1">Monitor admin</p>
                    </button>
                </div>

                <!-- Ringkasan Aktivitas -->
                <div class="mt-6 md:mt-8 pt-6 border-t border-slate-100">
                    <h3 class="font-medium text-gray-900 mb-3 md:mb-4">Ringkasan Aktivitas</h3>
                    <div class="space-y-3 md:space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm mr-3">
                                    SP
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Sarah Putri</p>
                                    <p class="text-xs text-gray-500">Login terakhir: 1 jam lalu</p>
                                </div>
                            </div>
                            <span
                                class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Aktif</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-sm mr-3">
                                    AW
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Agus Wijaya</p>
                                    <p class="text-xs text-gray-500">Login terakhir: 3 jam lalu</p>
                                </div>
                            </div>
                            <span
                                class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Aktif</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold text-sm mr-3">
                                    BS
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Budi Santoso</p>
                                    <p class="text-xs text-gray-500">Login terakhir: 2 minggu</p>
                                </div>
                            </div>
                            <span
                                class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">Nonaktif</span>
                        </div>
                    </div>

                    <div class="mt-4 md:mt-6">
                        <a href="../admin-pusat/index.html"
                            class="block text-center text-indigo-600 hover:text-indigo-800 text-sm font-medium py-2 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors">
                            <i class="fas fa-arrow-right mr-2"></i> Kelola Semua Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>
