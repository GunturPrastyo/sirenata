<x-landingpage::layouts.master title="SIRENATA - Sistem Informasi Perencanaan Ketenagakerjaan">
    <!-- Navigation -->
    <x-landingpage::navbar />

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center px-4 pt-24 pb-16 bg-white">
        <div class="max-w-6xl w-full text-center">
            <!-- Logo Icon -->
            <div class="mb-8 animate-fade-up">
                <div class="inline-block p-5 bg-slate-100 rounded-3xl">
                    <img src="{{ asset('images/logo.png') }}" alt="SIRENATA" class="h-20 w-20">
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-5xl md:text-7xl font-extrabold mb-4 animate-fade-up tracking-tight"
                style="color: #13416B; animation-delay: 0.1s;">
                SIRENATA
            </h1>

            <p class="text-xl md:text-2xl font-semibold mb-3 animate-fade-up"
                style="color: #13416B; animation-delay: 0.2s;">
                Sistem Informasi Perencanaan Ketenagakerjaan
            </p>

            <p class="text-base md:text-lg text-gray-500 max-w-2xl mx-auto mb-10 animate-fade-up"
                style="animation-delay: 0.3s;">
                Platform terpadu untuk manajemen dan perencanaan ketenagakerjaan di seluruh Indonesia, dilengkapi
                sistem pembelajaran daring (LMS) serta pelaporan RTK.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-14 animate-fade-up"
                style="animation-delay: 0.4s;">
                @guest
                    <a href="{{ route('register') }}"
                        class="px-8 py-3.5 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all text-base"
                        style="background-color: #13416B;">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-8 py-3.5 font-semibold rounded-xl border-2 transition-all text-base hover:bg-slate-50"
                        style="color: #13416B; border-color: #13416B;">
                        Masuk
                    </a>
                @endguest
                @auth
                    <a href="#courses"
                        class="px-8 py-3.5 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all text-base"
                        style="background-color: #13416B;">
                        Jelajahi Kursus
                    </a>
                @endauth
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 max-w-4xl mx-auto animate-fade-up"
                style="animation-delay: 0.5s;">
                <x-landingpage::stat-card title="Provinsi" :value="$stats['provinces']" />
                <x-landingpage::stat-card title="Kabupaten / Kota" :value="$stats['regencies']" />
                <x-landingpage::stat-card title="Dokumen RTK" :value="$stats['rtk']" />
                <x-landingpage::stat-card title="Pelatihan Aktif" :value="$stats['courses']" />
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 px-4 bg-slate-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <p class="text-sm font-bold uppercase tracking-widest mb-3" style="color: #13416B;">Fitur Utama</p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Solusi Terpadu Perencanaan Ketenagakerjaan</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Mengelola perencanaan tenaga kerja dari tingkat nasional hingga
                    kabupaten/kota, didukung platform e-learning untuk pengembangan kompetensi perencana.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <x-landingpage::feature-card title="Dashboard RTK" description="Perencanaan dan monitoring Rencana Tenaga Kerja Makro & Mikro secara terintegrasi dan real-time.">
                    <x-slot:icon>
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-5" style="background-color: #e8eef5;">
                            <svg class="w-7 h-7" style="color: #13416B;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </x-slot:icon>
                </x-landingpage::feature-card>

                <!-- Feature 2 -->
                <x-landingpage::feature-card title="Kalkulator RTK" description="Menyusun angka-angka Rencana Tenaga Kerja secara terstruktur dan akurat.">
                    <x-slot:icon>
                        <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3v-6M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                            </svg>
                        </div>
                    </x-slot:icon>
                </x-landingpage::feature-card>

                <!-- Feature 3 -->
                <x-landingpage::feature-card title="Pemanfaatan RTKD" description="Pengisian dan pemantauan apakah RTKD yang telah disusun sudah dimanfaatkan oleh daerah.">
                    <x-slot:icon>
                        <div class="w-14 h-14 bg-emerald-50 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </x-slot:icon>
                </x-landingpage::feature-card>

                <!-- Feature 4 -->
                <x-landingpage::feature-card title="Laporan & Analitik" description="Rekapitulasi data dan analisis RTK multi-level, dari tingkat nasional hingga kabupaten/kota.">
                    <x-slot:icon>
                        <div class="w-14 h-14 bg-amber-50 rounded-xl flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </x-slot:icon>
                </x-landingpage::feature-card>
            </div>
        </div>
    </section>

    <!-- Featured Courses Section -->
    @if($courses->count() > 0)
    <section id="courses" class="py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <p class="text-sm font-bold uppercase tracking-widest mb-3" style="color: #13416B;">E-Learning</p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Program Pelatihan Tersedia</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Tingkatkan kompetensi Anda sebagai perencana ketenagakerjaan
                    melalui program pelatihan daring yang terstruktur.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-{{ $courses->count() >= 4 ? '4' : $courses->count() }} gap-6">
                @foreach($courses as $course)
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow group">
                        <!-- Thumbnail -->
                        <div class="relative h-44 overflow-hidden" style="background-color: #e8eef5;">
                            @if($course->thumbnail_url)
                                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Category Badge -->
                            @if($course->category)
                                <span class="absolute top-3 left-3 px-3 py-1 text-xs font-bold rounded-full text-white"
                                    style="background-color: #13416B;">
                                    {{ $course->category->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">{{ $course->name }}</h3>

                            <div class="flex items-center gap-4 text-xs text-gray-400 mb-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    {{ $course->sections->count() }} Modul
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $course->sections->sum(fn($s) => $s->contents->count()) }} Materi
                                </span>
                            </div>

                            @guest
                                <a href="{{ route('login') }}"
                                    class="block w-full text-center py-2.5 text-sm font-semibold rounded-xl border-2 transition-all hover:text-white"
                                    style="color: #13416B; border-color: #13416B;"
                                    onmouseover="this.style.backgroundColor='#13416B'; this.style.color='#fff'"
                                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='#13416B'">
                                    Masuk untuk Mengikuti
                                </a>
                            @endguest
                            @auth
                                <a href="{{ route('user.course.my-course.detail', $course->slug) }}"
                                    class="block w-full text-center py-2.5 text-sm font-semibold rounded-xl text-white transition-all hover:opacity-90"
                                    style="background-color: #13416B;">
                                    Lihat Kursus
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Banner -->
    <section class="py-16 px-4" style="background-color: #13416B;">
        <div class="max-w-4xl mx-auto text-center">
            @guest
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Siap Memulai Perencanaan?</h2>
                <p class="text-slate-300 mb-8 max-w-xl mx-auto">
                    Bergabunglah dengan {{ $stats['regencies'] }}+ daerah di seluruh Indonesia yang telah menggunakan
                    SIRENATA untuk perencanaan ketenagakerjaan.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}"
                        class="px-8 py-3.5 bg-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all text-base"
                        style="color: #13416B;">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-8 py-3.5 font-semibold rounded-xl border-2 border-white text-white transition-all text-base hover:bg-white/10">
                        Sudah Punya Akun? Masuk
                    </a>
                </div>
            @endguest
            @auth
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">
                    Selamat Datang Kembali, {{ auth()->user()->name }}!
                </h2>
                <p class="text-slate-300 mb-8 max-w-xl mx-auto">
                    Lanjutkan aktivitas perencanaan ketenagakerjaan Anda dari dashboard.
                </p>
                <div class="flex items-center justify-center">
                    @role('super-admin')
                        <a href="{{ route('super-admin.dashboard') }}"
                            class="px-8 py-3.5 bg-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all text-base"
                            style="color: #13416B;">
                            Buka Dashboard
                        </a>
                    @endrole
                    @role('admin-pusat')
                        <a href="{{ route('admin-pusat.dashboard') }}"
                            class="px-8 py-3.5 bg-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all text-base"
                            style="color: #13416B;">
                            Buka Dashboard
                        </a>
                    @endrole
                    @role('admin-province')
                        <a href="{{ route('admin-province.dashboard') }}"
                            class="px-8 py-3.5 bg-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all text-base"
                            style="color: #13416B;">
                            Buka Dashboard
                        </a>
                    @endrole
                    @role('admin-kab-kota')
                        <a href="{{ route('admin-kab-kota.dashboard') }}"
                            class="px-8 py-3.5 bg-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all text-base"
                            style="color: #13416B;">
                            Buka Dashboard
                        </a>
                    @endrole
                    @role('user')
                        <a href="{{ route('user.dashboard') }}"
                            class="px-8 py-3.5 bg-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all text-base"
                            style="color: #13416B;">
                            Buka Dashboard
                        </a>
                    @endrole
                </div>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <x-landingpage::footer />
</x-landingpage::layouts.master>
