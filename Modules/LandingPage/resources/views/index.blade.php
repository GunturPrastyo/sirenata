<x-landingpage::layouts.master title="SIRENATA - Sistem Informasi Perencanaan Ketenagakerjaan">

    {{-- CSS Kustom untuk Animasi, Custom Scrollbar & Font Kalam --}}
    @push('styles')
        <style>
            /* Import Font Kalam dari Google Fonts */
            @import url('https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&display=swap');

            .font-kalam {
                font-family: 'Kalam', cursive;
            }

            html {
                scroll-behavior: smooth;
            }

            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            /* Custom Scrollbar untuk area Kursus */
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #e2e8f0;
                border-radius: 10px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #cbd5e1;
            }

            /* Floating Animasi */
            @keyframes float {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-15px) scale(1.01); }
            }

            @keyframes cardFloat {
                0%, 100% { transform: translateY(0) rotate(0deg); }
                33% { transform: translateY(-8px) rotate(1deg); }
                66% { transform: translateY(-4px) rotate(-1deg); }
            }

            @keyframes scrollVertical {
                0% { transform: translateY(0%); }
                100% { transform: translateY(-50%); }
            }

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            .animate-card-float-1 {
                animation: cardFloat 5s ease-in-out infinite;
            }

            .animate-card-float-2 {
                animation: cardFloat 7s ease-in-out infinite 0.5s;
            }

            .animate-card-float-3 {
                animation: cardFloat 6s ease-in-out infinite 1.5s;
            }

            .animate-scroll-y {
                animation: scrollVertical 25s linear infinite;
            }

            .animate-scroll-y:hover {
                animation-play-state: paused;
            }

            /* --- KELAS ANIMASI REVEAL DARI SAMPING / BAWAH --- */
            .reveal-left {
                opacity: 0;
                transform: translateX(-50px);
                transition: all 0.9s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }

            .reveal-right {
                opacity: 0;
                transform: translateX(50px);
                transition: all 0.9s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }

            .reveal-up {
                opacity: 0;
                transform: translateY(40px);
                transition: all 0.9s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }

            .reveal-left.active,
            .reveal-right.active,
            .reveal-up.active {
                opacity: 1;
                transform: translate(0, 0);
            }
        </style>
    @endpush

    <!-- Noise Overlay (Sangat Lembut) -->
    <div class="fixed inset-0 pointer-events-none z-[9999] opacity-[0.25] mix-blend-overlay"
        style="background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E&quot;);">
    </div>

    <!-- ========================================== -->
    <!-- NAVBAR (Sticky & Blur)                     -->
    <!-- ========================================== -->
    <nav x-data="{ isScrolled: false, mobileMenuOpen: false }" @scroll.window="isScrolled = (window.pageYOffset > 20)"
        :class="isScrolled ? 'bg-white/95 shadow-sm backdrop-blur-md' : 'bg-white/80 backdrop-blur-sm'"
        class="fixed top-0 left-0 right-0 z-[100] transition-all duration-300 border-b border-slate-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">

            <a href="{{ route('landingpage.index') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="SIRENATA Logo" class="h-8 sm:h-10 w-auto">
                <span class="text-lg sm:text-xl font-extrabold tracking-tight" style="color: #13416B;">SIRENATA</span>
            </a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center gap-2">
                <!-- Beranda ditambahkan kembali agar urutan natural -->
               
                <a href="#features"
                    class="text-slate-600 font-medium px-4 py-2 rounded-full hover:text-[#13416B] hover:bg-[#13416B]/10 transition-colors">Fitur</a>
                <a href="#courses"
                    class="text-slate-600 font-medium px-4 py-2 rounded-full hover:text-[#13416B] hover:bg-[#13416B]/10 transition-colors">LMS</a>
                <a href="#faq"
                    class="text-slate-600 font-medium px-4 py-2 rounded-full hover:text-[#13416B] hover:bg-[#13416B]/10 transition-colors">FAQ</a>
                <a href="#cta"
                    class="text-slate-600 font-medium px-4 py-2 rounded-full hover:text-[#13416B] hover:bg-[#13416B]/10 transition-colors">CTA</a>
            </div>

            <!-- Auth Buttons (Normal Landing Page) -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}"
                    class="px-5 py-2 font-bold text-slate-700 hover:bg-slate-100 rounded-full transition-colors">Masuk</a>
                <a href="{{ route('login') }}"
                    class="px-6 py-2.5 font-bold text-white rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all"
                    style="background-color: #13416B;">Daftar Gratis</a>
            </div>

            <!-- Mobile Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 focus:outline-none">
                <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                <i class="fas fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-collapse
            class="md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full">
            <div class="p-4 space-y-2">
                
                <a href="#features" @click="mobileMenuOpen = false"
                    class="block px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50 hover:text-[#13416B]">Fitur</a>
                <a href="#courses" @click="mobileMenuOpen = false"
                    class="block px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50 hover:text-[#13416B]">LMS</a>
                <a href="#faq" @click="mobileMenuOpen = false"
                    class="block px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50 hover:text-[#13416B]">FAQ</a>
                <a href="#cta" @click="mobileMenuOpen = false"
                    class="block px-4 py-3 rounded-xl font-bold text-slate-700 hover:bg-slate-50 hover:text-[#13416B]">CTA</a>
                <div class="pt-4 mt-2 border-t border-slate-100 flex gap-3">
                    <a href="{{ route('login') }}"
                        class="flex-1 text-center py-3 rounded-xl font-bold bg-slate-100 text-slate-700">Masuk</a>
                    <a href="{{ route('login') }}" class="flex-1 text-center py-3 rounded-xl font-bold text-white"
                        style="background-color: #13416B;">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ========================================== -->
    <!-- HERO SECTION (Split Layout & Kalem)        -->
    <!-- ========================================== -->
    <section class="min-h-screen pt-28 pb-16 flex items-center relative overflow-hidden bg-slate-50/50" id="home">
        <!-- Latar Belakang Dekoratif -->
        <div class="absolute inset-0 pointer-events-none z-0">
            <div
                class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:24px_24px] opacity-40">
            </div>
            <div
                class="absolute -top-[10%] -right-[5%] w-[500px] h-[500px] bg-[#13416B]/5 rounded-full blur-3xl animate-float">
            </div>
        </div>

        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center relative z-10">

            <!-- Kiri: Teks & CTA -->
            <div class="reveal-left">
                <span
                    class="inline-block py-1.5 px-4 rounded-full bg-[#13416B]/10 text-[#13416B] font-bold text-xs tracking-wider uppercase mb-5 border border-[#13416B]/20">
                    Platform Perencanaan Terpadu
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.15] mb-6">
                    Masa Depan <span class="text-[#13416B]">Ketenagakerjaan</span> Dimulai di Sini.
                </h1>
                <p class="text-base sm:text-lg text-slate-600 mb-8 leading-relaxed max-w-lg">
                    Platform terpadu untuk manajemen Rencana Tenaga Kerja (Makro & Mikro) dan evaluasi IPK, dilengkapi
                    fasilitas e-learning interaktif sebagai sarana transfer pengetahuan yang berkelanjutan dari pusat ke
                    daerah.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 mb-10">
                    <a href="{{ route('login') }}"
                        class="inline-flex justify-center items-center px-8 py-3.5 rounded-full text-white font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all"
                        style="background-color: #13416B;">
                        Daftar Sekarang
                    </a>
                    <a href="#features"
                        class="inline-flex justify-center items-center px-8 py-3.5 rounded-full border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition-colors">
                        Pelajari Fitur
                    </a>
                </div>

                <!-- Avatar Social Proof -->
                <div class="flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <img src="https://ui-avatars.com/api/?name=JD&background=random"
                            class="w-10 h-10 rounded-full border-2 border-white shadow-sm" alt="User">
                        <img src="https://ui-avatars.com/api/?name=FW&background=random"
                            class="w-10 h-10 rounded-full border-2 border-white shadow-sm" alt="User">
                        <img src="https://ui-avatars.com/api/?name=RM&background=random"
                            class="w-10 h-10 rounded-full border-2 border-white shadow-sm" alt="User">
                        <div
                            class="w-10 h-10 rounded-full border-2 border-white shadow-sm bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600">
                            +1K</div>
                    </div>
                    <p class="text-sm font-medium text-slate-600">Bergabung dengan pengguna lainnya.</p>
                </div>
            </div>

            <!-- Kanan: Floating Cards & Orang -->
            <div class="relative h-[550px] hidden lg:block reveal-right" style="transition-delay: 0.2s;">
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[420px] h-[420px] rounded-full border border-slate-200/60 animate-[spin_60s_linear_infinite] z-0">
                </div>

                <div
                    class="absolute bottom-0 left-1/2 transform -translate-x-1/2 z-20 w-[420px] pointer-events-none drop-shadow-2xl">
                    <img src="{{ asset('images/ilustrasi.webp') }}" alt="Ilustrasi Perencana"
                        class="w-full h-auto object-contain">
                </div>

                <div
                    class="absolute top-8 -right-4 w-[260px] bg-white rounded-2xl shadow-lg border border-slate-100 animate-card-float-1 z-10 overflow-hidden">
                    <div class="h-24 bg-[#184A78] flex items-center justify-center relative">
                        <span
                            class="absolute top-3 left-3 bg-white/10 border border-white/20 text-white text-[9px] font-bold px-2.5 py-1 rounded backdrop-blur-sm uppercase tracking-wider">Perkiraan</span>
                        <h2 class="text-[54px] font-medium text-white/95 leading-none"
                            style="font-family: Arial, sans-serif; letter-spacing: -2px;">PM</h2>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-slate-800 text-sm mb-1.5">Perencanaan Tenaga Kerja Makro</h3>
                        <p class="text-[10px] text-slate-500 mb-0 line-clamp-2">Penyusunan Rencana Tenaga Kerja dengan
                            pendekatan makro ekonomi dan ketenagakerjaan.</p>
                    </div>
                </div>

                <div
                    class="absolute bottom-12 -left-10 w-[250px] bg-white rounded-2xl shadow-xl border border-slate-100 animate-card-float-2 z-30 overflow-hidden">
                    <div class="h-20 bg-[#184A78] flex items-center justify-center relative">
                        <span
                            class="absolute top-2 left-2 bg-white/10 border border-white/20 text-white text-[8px] font-bold px-2 py-1 rounded backdrop-blur-sm uppercase tracking-wider">Perencanaan</span>
                        <h2 class="text-4xl font-medium text-white/95 leading-none"
                            style="font-family: Arial, sans-serif; letter-spacing: -1px;">PM</h2>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-slate-800 text-xs mb-1.5">Perencanaan Tenaga Kerja Mikro</h3>
                        <p class="text-[9px] text-slate-500 line-clamp-2 mb-0">Analisis kebutuhan tenaga kerja di
                            tingkat instansi atau perusahaan secara terperinci.</p>
                    </div>
                </div>

                <div
                    class="absolute top-40 -left-14 w-[240px] bg-white rounded-2xl shadow-md border border-slate-100 animate-card-float-3 z-10 overflow-hidden">
                    <div class="h-16 bg-[#184A78] flex items-center justify-center relative">
                        <span
                            class="absolute top-2 left-2 bg-white/10 border border-white/20 text-white text-[8px] font-bold px-2 py-0.5 rounded backdrop-blur-sm uppercase tracking-wider">Teori</span>
                        <h2 class="text-3xl font-medium text-white/95 leading-none"
                            style="font-family: Arial, sans-serif; letter-spacing: -1px;">IK</h2>
                    </div>
                    <div class="p-3">
                        <h3 class="font-bold text-slate-800 text-xs mb-1">Indeks Pembangunan Ketenagakerjaan</h3>
                        <p class="text-[9px] text-slate-500 line-clamp-2 mb-0">Pengukuran dan evaluasi 7 indikator
                            utama ketenagakerjaan daerah.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- STATS BANNER (Dengan Animasi Counter)      -->
    <!-- ========================================== -->
    @php
        function parseStat($val, $defaultSuffix = '')
        {
            $val = (string) $val;
            $num = floatval(preg_replace('/[^0-9.]/', '', $val));
            $suf = preg_replace('/[0-9.]/', '', $val);
            if (strpos($val, '+') !== false && strpos($suf, '+') === false) {
                $suf .= '+';
            }
            return ['num' => $num ? $num : 0, 'suf' => $suf ?: $defaultSuffix];
        }
        $sProv = parseStat($stats['provinces'] ?? 38);
        $sReg = parseStat($stats['regencies'] ?? 514);
        $sRtk = parseStat($stats['rtk'] ?? '1.2K', '+');
        $sCourse = parseStat($stats['courses'] ?? 15);
    @endphp

    <section class="py-12 bg-slate-900 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal-up">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x-0 md:divide-x divide-slate-700">
                <div class="text-center p-4">
                    <h4 class="text-4xl font-extrabold text-white mb-2 stat-counter" data-target="{{ $sProv['num'] }}"
                        data-suffix="{{ $sProv['suf'] }}">0</h4>
                    <p class="text-sm font-medium text-slate-400">Provinsi Terlibat</p>
                </div>
                <div class="text-center p-4">
                    <h4 class="text-4xl font-extrabold text-white mb-2 stat-counter"
                        data-target="{{ $sReg['num'] }}" data-suffix="{{ $sReg['suf'] }}">0</h4>
                    <p class="text-sm font-medium text-slate-400">Kabupaten/Kota</p>
                </div>
                <div class="text-center p-4">
                    <h4 class="text-4xl font-extrabold text-white mb-2 stat-counter"
                        data-target="{{ $sRtk['num'] }}" data-suffix="{{ $sRtk['suf'] }}">0</h4>
                    <p class="text-sm font-medium text-slate-400">Dokumen RTK</p>
                </div>
                <div class="text-center p-4">
                    <h4 class="text-4xl font-extrabold text-white mb-2 stat-counter"
                        data-target="{{ $sCourse['num'] }}" data-suffix="{{ $sCourse['suf'] }}">0</h4>
                    <p class="text-sm font-medium text-slate-400">Pelatihan Aktif</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- FITUR UTAMA                                -->
    <!-- ========================================== -->
    <section id="features" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-start">

                <div class="lg:sticky lg:top-32 reveal-left">
                    <!-- FONT KALAM DIAPLIKASIKAN DISINI -->
                    <span class="text-[#13416B] font-kalam font-bold text-md lg:text-xl mb-2 block tracking-wide">
                        Fitur Utama
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6 leading-tight">
                        Solusi Terpadu <span class="text-[#13416B]">Perencanaan Ketenagakerjaan</span>
                    </h2>
                    <p class="text-slate-600 text-lg leading-relaxed mb-8">
                        Aplikasi yang mendigitalkan pengumpulan data, perhitungan indeks, dan pemantauan capaian kinerja
                        daerah secara terukur dan konsisten.
                    </p>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3 text-slate-700 font-medium">
                            <i class="fas fa-check-circle text-[#13416B] opacity-80 mt-1"></i> Perhitungan Rencana
                            Tenaga Kerja (Makro & Mikro) otomatis.
                        </li>
                        <li class="flex items-start gap-3 text-slate-700 font-medium">
                            <i class="fas fa-check-circle text-[#13416B] opacity-80 mt-1"></i> Evaluasi 7 Indikator
                            Pembangunan Ketenagakerjaan.
                        </li>
                        <li class="flex items-start gap-3 text-slate-700 font-medium">
                            <i class="fas fa-check-circle text-[#13416B] opacity-80 mt-1"></i> Akses modul pembelajaran
                            interaktif berjenjang.
                        </li>
                    </ul>
                </div>

                <div class="relative h-[600px] overflow-hidden reveal-right"
                    style="mask-image: linear-gradient(to bottom, transparent, black 5%, black 95%, transparent);">
                    <div class="flex flex-col gap-6 animate-scroll-y hover:[animation-play-state:paused]">
                        @php
                            $features = [
                                [
                                    'icon' => 'fa-calculator',
                                    'title' => 'Kalkulator RTK',
                                    'desc' => 'Alat bantu simulasi perhitungan rencana tenaga kerja makro sesuai kondisi daerah.',
                                ],
                                [
                                    'icon' => 'fa-chart-pie',
                                    'title' => 'Pengukuran IPK',
                                    'desc' => 'Penilaian otomatis 7 indikator dengan verifikasi berjenjang dari pusat dan daerah.',
                                ],
                                [
                                    'icon' => 'fa-graduation-cap',
                                    'title' => 'LMS Terintegrasi',
                                    'desc' => 'Transfer pengetahuan terstruktur melalui modul pelatihan, video, dan sertifikasi.',
                                ],
                                [
                                    'icon' => 'fa-file-invoice',
                                    'title' => 'Pelaporan & Arsip',
                                    'desc' => 'Pemantauan dokumen RTKD dan fitur sanggahan nilai dengan bukti pendukung.',
                                ],
                            ];
                            $loopFeatures = array_merge($features, $features);
                        @endphp

                        @foreach ($loopFeatures as $feat)
                            <div
                                class="bg-slate-50 rounded-3xl p-8 border border-slate-100 flex items-start gap-5 mx-2 hover:border-[#13416B]/20 hover:shadow-sm transition-all">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-[#13416B] text-white flex items-center justify-center shrink-0 shadow-md border border-[#0f3354]">
                                    <i class="fas {{ $feat['icon'] }} text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800 mb-1.5">{{ $feat['title'] }}</h3>
                                    <p class="text-slate-600 leading-relaxed text-sm">{{ $feat['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- COURSES SECTION (LMS)                      -->
    <!-- ========================================== -->
    @if (isset($courses) && $courses->count() > 0)
        <section id="courses" class="py-24 px-4 bg-slate-50/50 border-t border-slate-200 relative overflow-hidden">
            <div class="max-w-7xl mx-auto">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

                    <!-- Kiri: Daftar Kursus Auto Scroll -->
                    <div class="relative h-[600px] overflow-hidden order-2 lg:order-1 reveal-left"
                        style="mask-image: linear-gradient(to bottom, transparent, black 5%, black 95%, transparent);">
                        <div
                            class="flex flex-col gap-5 animate-scroll-y hover:[animation-play-state:paused] pr-2 sm:pr-4">
                            @php
                                $loopCourses = collect($courses)->concat($courses)->concat($courses);
                            @endphp

                            @foreach ($loopCourses as $course)
                                @php
                                    $words = explode(' ', $course->name);
                                    $initials = '';
                                    foreach (array_slice($words, 0, 2) as $w) {
                                        $initials .= strtoupper(substr($w, 0, 1));
                                    }
                                    if (strlen($initials) < 2) {
                                        $initials = substr(strtoupper($course->name), 0, 2);
                                    }
                                @endphp

                                <a href="{{ route('user.course.my-course.detail', $course->slug) }}"
                                    class="block bg-white border border-slate-200 rounded-[20px] overflow-hidden hover:shadow-md hover:border-[#13416B]/30 hover:-translate-y-1 transition-all group mx-2">
                                    <div
                                        class="relative h-[120px] bg-[#184A78] flex items-center justify-center overflow-hidden">
                                        @if ($course->category)
                                            <span
                                                class="absolute top-4 left-4 px-2.5 py-1 text-[9px] font-bold rounded bg-transparent border border-white/30 text-white uppercase tracking-wider backdrop-blur-sm">
                                                {{ $course->category->name }}
                                            </span>
                                        @endif
                                        <h2 class="text-[56px] font-normal text-white leading-none tracking-tight"
                                            style="font-family: Arial, sans-serif;">
                                            {{ $initials }}
                                        </h2>
                                    </div>
                                    <div class="p-5">
                                        <h3
                                            class="font-bold text-slate-800 mb-1.5 line-clamp-1 text-base group-hover:text-[#13416B] transition-colors">
                                            {{ $course->name }}
                                        </h3>
                                        <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                                            {{ $course->description ?? "Deskripsi untuk {$course->name}. Kursus ini akan membahas dasar-dasar dan materi mendalam secara terstruktur." }}
                                        </p>
                                        <div class="flex items-center gap-2 text-[10px] font-medium text-slate-500">
                                            <span
                                                class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-md border border-slate-100">
                                                <i class="fas fa-layer-group text-slate-400"></i>
                                                {{ collect($course->sections)->count() }} Modul
                                            </span>
                                            <span
                                                class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-md border border-slate-100">
                                                <i class="fas fa-file-alt text-slate-400"></i>
                                                {{ collect($course->sections)->sum(fn($s) => collect(data_get($s, 'contents', []))->count()) }}
                                                Materi
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Kanan: Judul & Deskripsi Section Sticky -->
                    <div class="lg:sticky lg:top-16 order-1 lg:order-2 w-full text-left reveal-right">
                        <!-- FONT KALAM DIAPLIKASIKAN DISINI -->
                        <span class="text-[#13416B] font-kalam font-bold text-md lg:text-xl mb-2 block tracking-wide">
                            LMS Terintegrasi
                        </span>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6 leading-tight">
                            Tingkatkan Kapasitas <span class="text-[#13416B]">Aparatur Daerah</span>
                        </h2>
                        <p class="text-slate-600 text-lg leading-relaxed mb-8">
                            Fasilitas pelatihan daring terstruktur sebagai sarana transfer pengetahuan yang
                            berkelanjutan dari pusat ke daerah terkait Perencanaan Tenaga Kerja Makro, Mikro, dan IPK.
                        </p>

                        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm text-left">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-[#13416B] text-white rounded-xl flex items-center justify-center shrink-0 shadow-md border border-[#0f3354]">
                                    <i class="fas fa-award text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800">Sertifikat Kelulusan Resmi</h4>
                                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">Selesaikan rangkaian modul
                                        secara berurutan dan lulus post-test untuk mendapatkan sertifikat tanda
                                        kelulusan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 hidden lg:flex items-start gap-4 text-[#13416B]/60">
                            <svg width="120" height="60" viewBox="0 0 120 60" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="shrink-0 animate-pulse mt-1">
                                <path d="M115 15 C 80 15 40 40 15 40" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-dasharray="6 6" />
                                <path d="M25 30 L 12 40 L 25 50" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="font-bold italic tracking-wide text-sm opacity-90 leading-relaxed">
                                Selesaikan kursus <br> untuk mendapatkan sertifikat
                            </span>
                        </div>

                        <div class="mt-8 flex lg:hidden items-center justify-center gap-3 text-[#13416B]/60">
                            <span class="font-bold italic tracking-wide text-sm opacity-90">Selesaikan kursus untuk
                                mendapatkan sertifikat</span>
                            <i class="fas fa-arrow-down animate-bounce"></i>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endif

    <!-- ========================================== -->
    <!-- FAQ SECTION                                -->
    <!-- ========================================== -->
    <section id="faq" class="py-24 md:py-24 lg:py-12 px-4 bg-white border-t border-slate-200 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <!-- Kolom Kiri: Judul & Accordion -->
                <div class="reveal-left">
                    <div class="text-left mb-10">
                        <!-- FONT KALAM DIAPLIKASIKAN DISINI -->
                        <span class="text-[#13416B] font-kalam font-bold text-md lg:text-xl mb-2 block tracking-wide">
                            Pusat Bantuan
                        </span>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6 leading-tight">
                            Pertanyaan yang Sering <span class="text-[#13416B]">Diajukan</span>
                        </h2>
                        <p class="text-slate-600 text-lg leading-relaxed">
                            Temukan jawaban cepat untuk pertanyaan seputar penggunaan aplikasi SIRENATA atau kunjungi <a
                                href="https://bantuan.kemnaker.go.id/" target="_blank"
                                class="text-amber-500 font-bold hover:underline">Pusat Bantuan Kemnaker</a> untuk
                            kendala teknis lainnya.
                        </p>
                    </div>

                    <!-- Accordion FAQ menggunakan Alpine.js -->
                    <div x-data="{ activeAccordion: null }" class="space-y-4">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden transition-all hover:border-[#13416B]/30 hover:shadow-sm"
                            :class="{ 'border-[#13416B]/40 shadow-md bg-white': activeAccordion === 1 }">
                            <button @click="activeAccordion = activeAccordion === 1 ? null : 1"
                                class="w-full px-6 py-5 text-left flex items-center justify-between focus:outline-none">
                                <h3 class="font-bold text-slate-800 text-base sm:text-lg pr-4"
                                    :class="{ 'text-[#13416B]': activeAccordion === 1 }">
                                    Siapa saja yang dapat menggunakan aplikasi SIRENATA?
                                </h3>
                                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 shrink-0"
                                    :class="{ 'rotate-180 text-[#13416B]': activeAccordion === 1 }"></i>
                            </button>
                            <div x-show="activeAccordion === 1" x-collapse x-cloak>
                                <div
                                    class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100 pt-4 text-sm sm:text-base">
                                    Aplikasi ini ditujukan khusus bagi para pemangku kepentingan ketenagakerjaan,
                                    termasuk Super Admin, Admin Pusat, Admin Instansi Provinsi, Admin Instansi
                                    Kabupaten/Kota, dan Pengguna ASN (Aparatur Sipil Negara) selaku perencana di daerah.
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden transition-all hover:border-[#13416B]/30 hover:shadow-sm"
                            :class="{ 'border-[#13416B]/40 shadow-md bg-white': activeAccordion === 2 }">
                            <button @click="activeAccordion = activeAccordion === 2 ? null : 2"
                                class="w-full px-6 py-5 text-left flex items-center justify-between focus:outline-none">
                                <h3 class="font-bold text-slate-800 text-base sm:text-lg pr-4"
                                    :class="{ 'text-[#13416B]': activeAccordion === 2 }">
                                    Bagaimana cara mendaftar atau masuk ke dalam aplikasi?
                                </h3>
                                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 shrink-0"
                                    :class="{ 'rotate-180 text-[#13416B]': activeAccordion === 2 }"></i>
                            </button>
                            <div x-show="activeAccordion === 2" x-collapse x-cloak>
                                <div
                                    class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100 pt-4 text-sm sm:text-base">
                                    SIRENATA terintegrasi dengan sistem <strong>Single Sign-On (SSO)</strong> Kemnaker.
                                    Anda dapat langsung masuk menggunakan akun <strong>SIAPKerja ID</strong> yang telah
                                    terdaftar. Jika Anda mewakili instansi daerah, hubungi Admin Pusat untuk penyesuaian
                                    hak akses.
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden transition-all hover:border-[#13416B]/30 hover:shadow-sm"
                            :class="{ 'border-[#13416B]/40 shadow-md bg-white': activeAccordion === 3 }">
                            <button @click="activeAccordion = activeAccordion === 3 ? null : 3"
                                class="w-full px-6 py-5 text-left flex items-center justify-between focus:outline-none">
                                <h3 class="font-bold text-slate-800 text-base sm:text-lg pr-4"
                                    :class="{ 'text-[#13416B]': activeAccordion === 3 }">
                                    Apa perbedaan RTK Makro, RTK Mikro, dan IPK?
                                </h3>
                                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 shrink-0"
                                    :class="{ 'rotate-180 text-[#13416B]': activeAccordion === 3 }"></i>
                            </button>
                            <div x-show="activeAccordion === 3" x-collapse x-cloak>
                                <div
                                    class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100 pt-4 text-sm sm:text-base">
                                    <ul class="list-disc pl-5 space-y-2">
                                        <li><strong>RTK Makro:</strong> Proyeksi tenaga kerja di tingkat wilayah
                                            (Nasional/Provinsi/Kabupaten/Kota) berdasarkan ekonomi makro.</li>
                                        <li><strong>RTK Mikro:</strong> Analisis kebutuhan pegawai/tenaga kerja spesifik
                                            di dalam internal suatu instansi atau perusahaan.</li>
                                        <li><strong>IPK:</strong> Indeks Pembangunan Ketenagakerjaan, yaitu pengukuran
                                            capaian kinerja daerah berdasarkan 7 indikator utama ketenagakerjaan.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden transition-all hover:border-[#13416B]/30 hover:shadow-sm"
                            :class="{ 'border-[#13416B]/40 shadow-md bg-white': activeAccordion === 4 }">
                            <button @click="activeAccordion = activeAccordion === 4 ? null : 4"
                                class="w-full px-6 py-5 text-left flex items-center justify-between focus:outline-none">
                                <h3 class="font-bold text-slate-800 text-base sm:text-lg pr-4"
                                    :class="{ 'text-[#13416B]': activeAccordion === 4 }">
                                    Apa fungsi fitur LMS Terintegrasi?
                                </h3>
                                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-300 shrink-0"
                                    :class="{ 'rotate-180 text-[#13416B]': activeAccordion === 4 }"></i>
                            </button>
                            <div x-show="activeAccordion === 4" x-collapse x-cloak>
                                <div
                                    class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100 pt-4 text-sm sm:text-base">
                                    Fitur LMS difungsikan sebagai sarana transfer pengetahuan dari pusat ke daerah.
                                    Pengguna dapat mengikuti kursus interaktif secara mandiri untuk meningkatkan
                                    kompetensi terkait perencanaan, serta mendapatkan <strong>sertifikat resmi</strong>
                                    setelah lulus ujian (post-test).
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Ilustrasi Bantuan -->
                <div class="relative hidden lg:flex justify-center items-center reveal-right h-full">
                    <div
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-[380px] h-[380px] bg-amber-400 rounded-full z-0">
                    </div>
                    <img src="{{ asset('images/faq-illustration.webp') }}" alt="Pusat Bantuan Kemnaker"
                        class="relative z-10 w-full max-w-[520px] h-auto object-contain drop-shadow-2xl animate-float mt-8"
                        style="-webkit-mask-image: linear-gradient(to bottom, black 75%, transparent 100%); mask-image: linear-gradient(to bottom, black 75%, transparent 100%);">
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- CTA SECTION        -->
    <!-- ========================================== -->
    <section id="cta" class="py-24 md:py-36 lg:py-8 px-4 md:px-16 relative overflow-hidden" style="background-color: #13416B;" >
        <!-- Efek Glow Latar Belakang -->
        <div
            class="absolute inset-0 bg-blue-400/20 blur-[120px] rounded-full w-[80%] h-[80%] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none z-0">
        </div>

        <!-- Ornamen SVG Raksasa (V Kebalik / Inverted V) -->
        <div
            class="absolute -left-[30%] sm:-left-[10%] top-1/2 -translate-y-1/2 w-[700px] h-[700px] lg:w-[1100px] lg:h-[1100px] text-white opacity-[0.05] pointer-events-none z-0 transition-transform duration-1000">
            <svg viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                <!-- Titik tumpu V berada di tengah atas -->
                <g transform="translate(200, 80)">
                    <!-- Kaki Kiri (Miring ke kiri bawah) -->
                    <rect x="-45" y="-20" width="90" height="380" rx="45" transform="rotate(45)"
                        fill="currentColor" />
                    <!-- Kaki Kanan (Miring ke kanan bawah) -->
                    <rect x="-45" y="-20" width="90" height="380" rx="45" transform="rotate(-45)"
                        fill="currentColor" />
                </g>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                <!-- Kolom Kiri: Teks & Tombol -->
                <div class="text-left reveal-left lg:col-span-7 xl:col-span-8 lg:pr-10">
                    <h2
                        class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 leading-tight drop-shadow-sm">
                        Siap Memulai Perencanaan?
                    </h2>
                    <p class="text-slate-300 mb-10 max-w-2xl text-md md:text-xl leading-relaxed">
                        Tingkatkan efisiensi dan akurasi data dengan bergabung bersama
                        {{ $stats['regencies'] ?? 514 }}+ daerah lain di seluruh Indonesia menggunakan SIRENATA.
                    </p>

                    <div class="flex flex-col sm:flex-row items-start gap-4 reveal-up"
                        style="transition-delay: 0.2s;">
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto px-8 py-4 bg-white font-bold rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all text-md md:text-lg text-center"
                            style="color: #13416B;">
                            Daftar Gratis Sekarang
                        </a>
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto px-8 py-4 font-bold rounded-full border-2 border-white/30 text-white transition-all hover:bg-white/10 text-md md:text-lg text-center">
                            Sudah Punya Akun? Masuk
                        </a>
                    </div>
                </div>

                <!-- Kolom Kanan: Tempat Ilustrasi Orang -->
                <div
                    class="relative hidden lg:flex justify-end items-end reveal-right h-full lg:col-span-5 xl:col-span-4">
                    <div class="absolute right-10 bottom-10 w-[300px] h-[300px] bg-white/5 rounded-full blur-xl z-0">
                    </div>
                    <img src="{{ asset('images/cta-illustration.webp') }}"
                        alt="Kepala Pusat Perencanaan Ketenagakerjaan"
                        class="relative z-10 w-full max-w-[360px] lg:max-w-[340px] h-auto object-contain drop-shadow-2xl animate-float translate-y-8"
                        style="-webkit-mask-image: linear-gradient(to bottom, black 80%, transparent 100%); mask-image: linear-gradient(to bottom, black 80%, transparent 100%);">
                </div>

            </div>
        </div>
    </section>

   <!-- ========================================== -->
    <!-- FOOTER                                     -->
    <!-- ========================================== -->
    <footer class="relative py-20 px-4 overflow-hidden" style="background-color: #0b2641;">
        <!-- Dekorasi Latar Belakang Footer -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[500px] h-[500px] rounded-full bg-blue-500/5 blur-[100px] pointer-events-none z-0"></div>

        <div class="max-w-7xl mx-auto relative z-10 reveal-up">
            <div class="flex flex-col lg:flex-row gap-12 lg:gap-24 mb-16">
                
                <!-- Kiri: Brand, Deskripsi & Sosial Media -->
                <div class="lg:w-5/12 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-4 mb-6">
                            <img src="{{ asset('images/logo.png') }}" alt="SIRENATA" class="h-12 w-auto brightness-0 invert">
                            <span class="text-3xl font-extrabold text-white tracking-tight">SIRENATA</span>
                        </div>
                        <p class="text-slate-400 leading-relaxed text-base mb-10 max-w-md">
                            Aplikasi digital terpadu untuk kebutuhan penyusunan RTK Makro, RTK Mikro, dan pengukuran Indeks Pembangunan Ketenagakerjaan.
                        </p>
                    </div>

                    <!-- Sosial Media -->
                    <div>
                        <h3 class="font-bold text-white mb-5 uppercase tracking-widest text-xs opacity-60">Terhubung Bersama Kami</h3>
                        <div class="flex items-center gap-4">
                            <a href="#" class="group w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 hover:bg-[#13416B] hover:text-white hover:border-[#13416B] hover:-translate-y-1 transition-all duration-300 shadow-lg">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                            <a href="#" class="group w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 hover:bg-[#13416B] hover:text-white hover:border-[#13416B] hover:-translate-y-1 transition-all duration-300 shadow-lg">
                                <i class="fab fa-youtube text-xl"></i>
                            </a>
                            <!-- Ikon X Menggunakan Inline SVG agar pasti muncul -->
                            <a href="#" class="group w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-300 hover:bg-[#13416B] hover:text-white hover:border-[#13416B] hover:-translate-y-1 transition-all duration-300 shadow-lg">
                                <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Tautan & Hubungi Kami (Layout Kalem) -->
                <div class="lg:w-7/12 grid grid-cols-1 sm:grid-cols-2 gap-10 lg:gap-16 pt-2">
                    
                    <!-- Tautan Publik -->
                    <div>
                        <h3 class="font-bold text-white mb-6 uppercase tracking-widest text-xs border-b border-white/10 pb-4 inline-block">Tautan Publik</h3>
                        <ul class="space-y-4 text-slate-400 text-sm">
                            <li>
                                <a href="https://kemnaker.go.id" target="_blank" class="hover:text-white transition-colors flex items-center gap-2 group">
                                    <i class="fas fa-arrow-right text-[10px] opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300 text-blue-400"></i> Kemnaker RI
                                </a>
                            </li>
                            <li>
                                <a href="https://siapkerja.kemnaker.go.id" target="_blank" class="hover:text-white transition-colors flex items-center gap-2 group">
                                    <i class="fas fa-arrow-right text-[10px] opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300 text-blue-400"></i> SIAPkerja Kemnaker
                                </a>
                            </li>
                            <li>
                                <a href="https://satudata.kemnaker.go.id" target="_blank" class="hover:text-white transition-colors flex items-center gap-2 group">
                                    <i class="fas fa-arrow-right text-[10px] opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300 text-blue-400"></i> Satu Data Ketenagakerjaan
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Hubungi Kami (Kalem & Rata Kiri) -->
                    <div>
                        <h3 class="font-bold text-white mb-6 uppercase tracking-widest text-xs border-b border-white/10 pb-4 inline-block">Pusat Informasi</h3>
                        
                        <ul class="space-y-5 text-slate-400 text-sm">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-envelope mt-1 shrink-0 text-white"></i>
                                <div>
                                    <a href="mailto:support@sirenata.go.id" class="hover:text-white transition-colors">support@sirenata.go.id</a>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt mt-1 shrink-0 text-white"></i>
                                <div>
                                    <span>Kementerian Ketenagakerjaan RI<br>Jakarta, Indonesia</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Garis Bawah & Copyright -->
            <div class="pt-8 border-t border-slate-700/50 flex flex-col md:flex-row items-center justify-between gap-4 text-slate-400 text-sm font-medium">
                <p>&copy; 2026 Kementerian Ketenagakerjaan Republik Indonesia.</p>
                <p class="text-xs opacity-60 tracking-wider">ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

    <!-- ========================================== -->
    <!-- SCROLL TO TOP BUTTON                       -->
    <!-- ========================================== -->
    <div x-data="{ showScrollTop: false }" @scroll.window="showScrollTop = (window.pageYOffset > 400)"
        class="fixed bottom-6 right-6 z-50">
        <button x-show="showScrollTop" @click="window.scrollTo({top: 0, behavior: 'smooth'})"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10"
            class="w-12 h-12 flex items-center justify-center bg-[#13416B] text-white rounded-full shadow-lg hover:bg-blue-800 hover:-translate-y-1 transition-all focus:outline-none border-2 border-white/20">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>

    <!-- SCRIPT OBSERVER UNTUK ANIMASI MUNCUL (REVEAL) -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const revealOptions = {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.15
                };

                const revealObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('active');
                        }
                    });
                }, revealOptions);

                const revealElements = document.querySelectorAll('.reveal-left, .reveal-right, .reveal-up');
                revealElements.forEach(el => revealObserver.observe(el));

                const statOptions = {
                    root: null,
                    rootMargin: '0px',
                    threshold: 0.5
                };

                const statObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const targetEl = entry.target;
                            const targetNum = parseFloat(targetEl.getAttribute('data-target'));
                            const isFloat = targetNum % 1 !== 0;
                            const suffix = targetEl.getAttribute('data-suffix') || '';
                            const duration = 2000;
                            let startTime = null;

                            const animateCount = (timestamp) => {
                                if (!startTime) startTime = timestamp;
                                const progress = Math.min((timestamp - startTime) / duration, 1);

                                const easeProgress = 1 - Math.pow(1 - progress, 3);
                                let currentNum = easeProgress * targetNum;

                                if (isFloat) {
                                    targetEl.innerText = currentNum.toFixed(1) + suffix;
                                } else {
                                    targetEl.innerText = Math.floor(currentNum) + suffix;
                                }

                                if (progress < 1) {
                                    requestAnimationFrame(animateCount);
                                } else {
                                    targetEl.innerText = targetNum + suffix;
                                }
                            };
                            requestAnimationFrame(animateCount);
                            observer.unobserve(targetEl);
                        }
                    });
                }, statOptions);

                const statCounters = document.querySelectorAll('.stat-counter');
                statCounters.forEach(counter => statObserver.observe(counter));
            });
        </script>
    @endpush

</x-landingpage::layouts.master>