<x-landingpage::layouts.master title="Home Page">
    <!-- Navigation -->
    <x-landingpage::navbar />

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center px-4 pt-24 pb-16">
        <div class="max-w-6xl w-full text-center">
            <!-- Logo Icon -->
            <div class="mb-8 animate-fade-up">
                <div class="inline-block p-4 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-3xl">
                    <img src="{{ asset('images/logo.png') }}" alt="SIRENATA" class="h-20 w-20">
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-up"
                style="color: #13416B; animation-delay: 0.1s;">
                SIRENATA
            </h1>

            <p class="text-2xl md:text-3xl text-gray-700 font-semibold mb-4 animate-fade-up"
                style="animation-delay: 0.2s;">
                Sistem Informasi Perencanaan Ketenagakerjaan
            </p>

            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-12 animate-fade-up"
                style="animation-delay: 0.3s;">
                Platform terpadu untuk manajemen dan perencanaan ketenagakerjaan di Indonesia
            </p>


            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto animate-fade-up"
                style="animation-delay: 0.5s;">
                <x-landingpage::statcard title="Provinsi" value="38" />
                <x-landingpage::statcard title="Kab/Kota" value="514" />
                <x-landingpage::statcard title="PTK" value="PTK" />
                <x-landingpage::statcard title="LMS" value="LMS" />
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900  mb-4">Fitur Utama SIRENATA</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">Solusi lengkap untuk manajemen RTK dan pengembangan
                    kompetensi</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <x-landingpage::featurecard title="Dashboard RTK"
                    description="Perencanaan dan monitoring RTK Makro & Mikro terintegrasi">
                    <x-slot name="icon">
                        <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </x-slot>
                </x-landingpage::featurecard>

                <x-landingpage::featurecard title="Katalog Kursus"
                    description="Pelatihan PTK Makro, PTK Mikro, dan kompetensi profesional">
                    <x-slot name="icon">
                        <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </x-slot>
                </x-landingpage::featurecard>

                <x-landingpage::featurecard title="Perpustakaan Digital"
                    description="E-books, modul, dan resources pembelajaran lengkap">
                    <x-slot name="icon">
                        <div class="w-16 h-16 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                        </div>
                    </x-slot>
                </x-landingpage::featurecard>
                <x-landingpage::featurecard title="Laporan & Analitik"
                    description="Rekapitulasi data dan analisis RTK multi-level">
                    <x-slot name="icon">
                        <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </x-slot>
                </x-landingpage::featurecard>
            </div>
        </div>
    </section>



    <!-- Footer -->
    <x-landingpage::footer />
</x-landingpage::layouts.master>
