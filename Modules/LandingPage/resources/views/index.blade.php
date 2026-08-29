<x-landingpage::layouts.master title="SIRENATA - Sistem Informasi Perencanaan Ketenagakerjaan">
    
    {{-- CSS Kustom untuk Animasi & Custom Scrollbar --}}
    @push('styles')
    <style>
        html { scroll-behavior: smooth; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Custom Scrollbar untuk area Kursus */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        
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
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-card-float-1 { animation: cardFloat 5s ease-in-out infinite; }
        .animate-card-float-2 { animation: cardFloat 7s ease-in-out infinite 0.5s; }
        .animate-card-float-3 { animation: cardFloat 6s ease-in-out infinite 1.5s; }
        .animate-fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        /* Animasi auto-scroll vertikal */
        .animate-scroll-y { animation: scrollVertical 25s linear infinite; }
        .animate-scroll-y:hover { animation-play-state: paused; }
    </style>
    @endpush

    <!-- Noise Overlay (Sangat Lembut) -->
    <div class="fixed inset-0 pointer-events-none z-[9999] opacity-[0.25] mix-blend-overlay" style="background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E&quot;);"></div>

    <!-- ========================================== -->
    <!-- NAVBAR (Sticky & Blur)                     -->
    <!-- ========================================== -->
    <nav x-data="{ isScrolled: false, mobileMenuOpen: false }" 
         @scroll.window="isScrolled = (window.pageYOffset > 20)"
         :class="isScrolled ? 'bg-white/95 shadow-sm backdrop-blur-md' : 'bg-white/80 backdrop-blur-sm'"
         class="fixed top-0 left-0 right-0 z-[100] transition-all duration-300 border-b border-slate-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            
            <a href="{{ route('landingpage.index') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="SIRENATA Logo" class="h-8 sm:h-10 w-auto">
                <span class="text-lg sm:text-xl font-extrabold tracking-tight" style="color: #13416B;">SIRENATA</span>
            </a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center gap-2">
                <a href="#fitur" class="text-slate-600 font-medium px-4 py-2 rounded-full hover:text-[#13416B] hover:bg-[#13416B]/10 transition-colors">Fitur</a>
                <a href="#courses" class="text-slate-600 font-medium px-4 py-2 rounded-full hover:text-[#13416B] hover:bg-[#13416B]/10 transition-colors">E-Learning</a>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="px-5 py-2 font-bold text-slate-700 hover:bg-slate-100 rounded-full transition-colors">Masuk</a>
                    <a href="{{ route('login') }}" class="px-6 py-2.5 font-bold text-white rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all" style="background-color: #13416B;">Daftar Gratis</a>
                @endguest
                @auth
                    <a href="{{ route('user.dashboard') }}" class="px-6 py-2.5 font-bold text-white rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all" style="background-color: #13416B;">Buka Dashboard</a>
                @endauth
            </div>

            <!-- Mobile Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 focus:outline-none">
                <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                <i class="fas fa-times text-xl" x-show="mobileMenuOpen" x-cloak></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-collapse class="md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full">
            <div class="p-4 space-y-2">
                <a href="#fitur" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-xl font-bold text-slate-700 bg-slate-50 hover:text-[#13416B]">Fitur</a>
                <a href="#courses" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-xl font-bold text-slate-700 bg-slate-50 hover:text-[#13416B]">E-Learning</a>
                <div class="pt-4 mt-2 border-t border-slate-100 flex gap-3">
                    <a href="{{ route('login') }}" class="flex-1 text-center py-3 rounded-xl font-bold bg-slate-100 text-slate-700">Masuk</a>
                    <a href="{{ route('login') }}" class="flex-1 text-center py-3 rounded-xl font-bold text-white" style="background-color: #13416B;">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ========================================== -->
    <!-- HERO SECTION (Split Layout & Kalem)        -->
    <!-- ========================================== -->
    <section class="min-h-screen pt-28 pb-16 flex items-center relative overflow-hidden bg-slate-50/50" id="home">
        <!-- Latar Belakang Dekoratif (Sangat Lembut) -->
        <div class="absolute inset-0 pointer-events-none z-0">
            <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:24px_24px] opacity-40"></div>
            <div class="absolute -top-[10%] -right-[5%] w-[500px] h-[500px] bg-[#13416B]/5 rounded-full blur-3xl animate-float"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center relative z-10">
            
            <!-- Kiri: Teks & CTA -->
            <div>
                <span class="inline-block py-1.5 px-4 rounded-full bg-[#13416B]/10 text-[#13416B] font-bold text-xs tracking-wider uppercase mb-5 border border-[#13416B]/20">
                    Platform Perencanaan Terpadu
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.15] mb-6">
                    Masa Depan <span class="text-[#13416B]">Ketenagakerjaan</span> Dimulai di Sini.
                </h1>
                <p class="text-base sm:text-lg text-slate-600 mb-8 leading-relaxed max-w-lg">
                    Platform terpadu untuk manajemen Rencana Tenaga Kerja (Makro & Mikro) dan evaluasi IPK, dilengkapi fasilitas e-learning interaktif sebagai sarana transfer pengetahuan yang berkelanjutan dari pusat ke daerah.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3 mb-10">
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-8 py-3.5 rounded-full text-white font-bold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all" style="background-color: #13416B;">
                        Daftar Sekarang
                    </a>
                    <a href="#fitur" class="inline-flex justify-center items-center px-8 py-3.5 rounded-full border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 transition-colors">
                        Pelajari Fitur
                    </a>
                </div>

                <!-- Avatar Social Proof -->
                <div class="flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <img src="https://ui-avatars.com/api/?name=A&background=random" class="w-10 h-10 rounded-full border-2 border-white shadow-sm" alt="User">
                        <img src="https://ui-avatars.com/api/?name=B&background=random" class="w-10 h-10 rounded-full border-2 border-white shadow-sm" alt="User">
                        <img src="https://ui-avatars.com/api/?name=C&background=random" class="w-10 h-10 rounded-full border-2 border-white shadow-sm" alt="User">
                        <div class="w-10 h-10 rounded-full border-2 border-white shadow-sm bg-slate-50 flex items-center justify-center text-xs font-bold text-slate-600">+1K</div>
                    </div>
                    <p class="text-sm font-medium text-slate-600">Bergabung dengan pengguna lainnya.</p>
                </div>
            </div>

            <!-- Kanan: Floating Cards Mockup (Warna Seragam: #184A78) -->
            <div class="relative h-[500px] hidden lg:block">
                <!-- Lingkaran Latar (Sangat Lembut) -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[320px] h-[320px] rounded-full border border-slate-200/60 animate-[spin_60s_linear_infinite]"></div>

                <!-- Card 1: Perencanaan Tenaga Kerja Makro -->
                <div class="absolute top-0 right-0 w-[260px] bg-white rounded-2xl shadow-lg border border-slate-100 animate-card-float-1 z-20 overflow-hidden">
                    <div class="h-24 bg-[#184A78] flex items-center justify-center relative">
                        <span class="absolute top-3 left-3 bg-white/10 border border-white/20 text-white text-[9px] font-bold px-2.5 py-1 rounded backdrop-blur-sm uppercase tracking-wider">Perkiraan</span>
                        <h2 class="text-[54px] font-medium text-white/95 leading-none" style="font-family: Arial, sans-serif; letter-spacing: -2px;">PM</h2>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-slate-800 text-sm mb-1.5">Perencanaan Tenaga Kerja Makro</h3>
                        <p class="text-[10px] text-slate-500 mb-0 line-clamp-2">Penyusunan Rencana Tenaga Kerja dengan pendekatan makro ekonomi dan ketenagakerjaan.</p>
                    </div>
                </div>

                <!-- Card 2: Perencanaan Tenaga Kerja Mikro -->
                <div class="absolute bottom-12 left-0 w-[250px] bg-white rounded-2xl shadow-md border border-slate-100 animate-card-float-2 z-30 overflow-hidden">
                    <div class="h-20 bg-[#184A78] flex items-center justify-center relative">
                        <span class="absolute top-2 left-2 bg-white/10 border border-white/20 text-white text-[8px] font-bold px-2 py-1 rounded backdrop-blur-sm uppercase tracking-wider">Perencanaan</span>
                        <h2 class="text-4xl font-medium text-white/95 leading-none" style="font-family: Arial, sans-serif; letter-spacing: -1px;">PM</h2>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-slate-800 text-xs mb-1.5">Perencanaan Tenaga Kerja Mikro</h3>
                        <p class="text-[9px] text-slate-500 line-clamp-2 mb-0">Analisis kebutuhan tenaga kerja di tingkat instansi atau perusahaan secara terperinci.</p>
                    </div>
                </div>

                <!-- Card 3: IPK -->
                <div class="absolute top-44 -left-10 w-[240px] bg-white rounded-2xl shadow-md border border-slate-100 animate-card-float-3 z-10 overflow-hidden">
                    <div class="h-16 bg-[#184A78] flex items-center justify-center relative">
                        <span class="absolute top-2 left-2 bg-white/10 border border-white/20 text-white text-[8px] font-bold px-2 py-0.5 rounded backdrop-blur-sm uppercase tracking-wider">Teori</span>
                        <h2 class="text-3xl font-medium text-white/95 leading-none" style="font-family: Arial, sans-serif; letter-spacing: -1px;">IK</h2>
                    </div>
                    <div class="p-3">
                        <h3 class="font-bold text-slate-800 text-xs mb-1">Indeks Pembangunan Ketenagakerjaan</h3>
                        <p class="text-[9px] text-slate-500 line-clamp-2 mb-0">Pengukuran dan evaluasi 7 indikator utama ketenagakerjaan daerah.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- STATS BANNER                               -->
    <!-- ========================================== -->
    <section class="py-12 bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x-0 md:divide-x divide-slate-700">
                <div class="text-center p-4">
                    <h4 class="text-4xl font-extrabold text-white mb-2">{{ $stats['provinces'] ?? 38 }}</h4>
                    <p class="text-sm font-medium text-slate-400">Provinsi Terlibat</p>
                </div>
                <div class="text-center p-4">
                    <h4 class="text-4xl font-extrabold text-white mb-2">{{ $stats['regencies'] ?? 514 }}</h4>
                    <p class="text-sm font-medium text-slate-400">Kabupaten/Kota</p>
                </div>
                <div class="text-center p-4">
                    <h4 class="text-4xl font-extrabold text-white mb-2">{{ $stats['rtk'] ?? '1.2K' }}+</h4>
                    <p class="text-sm font-medium text-slate-400">Dokumen RTK</p>
                </div>
                <div class="text-center p-4">
                    <h4 class="text-4xl font-extrabold text-white mb-2">{{ $stats['courses'] ?? 15 }}</h4>
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
                
                <div class="lg:sticky lg:top-32">
                    <span class="text-[#13416B] font-bold tracking-wider text-sm mb-3 block uppercase">Fitur Utama</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6 leading-tight">
                        Solusi Terpadu <span class="text-[#13416B]">Perencanaan Ketenagakerjaan</span>
                    </h2>
                    <p class="text-slate-600 text-lg leading-relaxed mb-8">
                        Aplikasi yang mendigitalkan pengumpulan data, perhitungan indeks, dan pemantauan capaian kinerja daerah secara terukur dan konsisten.
                    </p>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3 text-slate-700 font-medium">
                            <i class="fas fa-check-circle text-[#13416B] opacity-80 mt-1"></i> Perhitungan Rencana Tenaga Kerja (Makro & Mikro) otomatis.
                        </li>
                        <li class="flex items-start gap-3 text-slate-700 font-medium">
                            <i class="fas fa-check-circle text-[#13416B] opacity-80 mt-1"></i> Evaluasi 7 Indikator Pembangunan Ketenagakerjaan.
                        </li>
                        <li class="flex items-start gap-3 text-slate-700 font-medium">
                            <i class="fas fa-check-circle text-[#13416B] opacity-80 mt-1"></i> Akses modul pembelajaran interaktif berjenjang.
                        </li>
                    </ul>
                </div>

                <div class="relative h-[600px] overflow-hidden" style="mask-image: linear-gradient(to bottom, transparent, black 5%, black 95%, transparent);">
                    <div class="flex flex-col gap-6 animate-scroll-y hover:[animation-play-state:paused]">
                        @php
                            $features = [
                                ['icon' => 'fa-calculator', 'title' => 'Kalkulator RTK', 'desc' => 'Alat bantu simulasi perhitungan rencana tenaga kerja makro sesuai kondisi daerah.'],
                                ['icon' => 'fa-chart-pie', 'title' => 'Pengukuran IPK', 'desc' => 'Penilaian otomatis 7 indikator dengan verifikasi berjenjang dari pusat dan daerah.'],
                                ['icon' => 'fa-graduation-cap', 'title' => 'LMS Terintegrasi', 'desc' => 'Transfer pengetahuan terstruktur melalui modul pelatihan, video, dan sertifikasi.'],
                                ['icon' => 'fa-file-invoice', 'title' => 'Pelaporan & Arsip', 'desc' => 'Pemantauan dokumen RTKD dan fitur sanggahan nilai dengan bukti pendukung.'],
                            ];
                            $loopFeatures = array_merge($features, $features);
                        @endphp

                        @foreach($loopFeatures as $feat)
                            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 flex items-start gap-5 mx-2 hover:border-[#13416B]/20 hover:shadow-sm transition-all">
                                <div class="w-14 h-14 rounded-2xl bg-[#13416B]/10 text-[#13416B] flex items-center justify-center shrink-0">
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
    <!-- COURSES SECTION (Scroll Auto Kiri - Kanan Deskripsi Sticky) -->
    <!-- ========================================== -->
    @if(isset($courses) && $courses->count() > 0)
    <section id="courses" class="py-24 px-4 bg-slate-50/50 border-t border-slate-200 relative overflow-hidden">
        <div class="max-w-7xl mx-auto">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">
                
                <!-- Kiri: Daftar Kursus Auto Scroll (Tanpa Tombol Akses) -->
                <div class="relative h-[600px] overflow-hidden order-2 lg:order-1" style="mask-image: linear-gradient(to bottom, transparent, black 5%, black 95%, transparent);">
                    <!-- Wrapper Animasi Scroll Auto -->
                    <div class="flex flex-col gap-5 animate-scroll-y hover:[animation-play-state:paused] pr-2 sm:pr-4">
                        @php
                            // Menggandakan array agar efek scroll berjalan tanpa henti
                            $loopCourses = collect($courses)->concat($courses)->concat($courses);
                        @endphp

                        @foreach($loopCourses as $course)
                            @php
                                // Ambil inisial nama kursus (maks 2 huruf)
                                $words = explode(' ', $course->name);
                                $initials = '';
                                foreach(array_slice($words, 0, 2) as $w) { $initials .= strtoupper(substr($w, 0, 1)); }
                                if(strlen($initials) < 2) $initials = substr(strtoupper($course->name), 0, 2);
                            @endphp

                            <!-- Card Kursus Bersih -->
                            <a href="{{ route('user.course.my-course.detail', $course->slug) }}" class="block bg-white border border-slate-200 rounded-[20px] overflow-hidden hover:shadow-md hover:border-[#13416B]/30 hover:-translate-y-1 transition-all group mx-2">
                                
                                {{-- Header Kartu (Warna Biru sesuai gambar) --}}
                                <div class="relative h-[120px] bg-[#184A78] flex items-center justify-center overflow-hidden">
                                    @if($course->category)
                                        <span class="absolute top-4 left-4 px-2.5 py-1 text-[9px] font-bold rounded bg-transparent border border-white/30 text-white uppercase tracking-wider backdrop-blur-sm">
                                            {{ $course->category->name }}
                                        </span>
                                    @endif
                                    <h2 class="text-[56px] font-normal text-white leading-none tracking-tight" style="font-family: Arial, sans-serif;">
                                        {{ $initials }}
                                    </h2>
                                </div>

                                {{-- Konten Kartu --}}
                                <div class="p-5">
                                    <h3 class="font-bold text-slate-800 mb-1.5 line-clamp-1 text-base group-hover:text-[#13416B] transition-colors">
                                        {{ $course->name }}
                                    </h3>
                                    
                                    <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                                        {{ $course->description ?? "Deskripsi untuk {$course->name}. Kursus ini akan membahas dasar-dasar dan materi mendalam secara terstruktur." }}
                                    </p>

                                    <!-- Info Modul & Materi -->
                                    <div class="flex items-center gap-2 text-[10px] font-medium text-slate-500">
                                        <span class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-md border border-slate-100">
                                            <i class="fas fa-layer-group text-slate-400"></i> {{ collect($course->sections)->count() }} Modul
                                        </span>
                                        <span class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-md border border-slate-100">
                                            <i class="fas fa-file-alt text-slate-400"></i> {{ collect($course->sections)->sum(fn($s) => collect(data_get($s, 'contents', []))->count()) }} Materi
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Kanan: Judul & Deskripsi Section Sticky (Teks Rata Kiri) -->
                <div class="lg:sticky lg:top-32 order-1 lg:order-2 w-full text-left">
                    <span class="text-[#13416B] font-bold tracking-wider text-sm mb-3 block uppercase">E-Learning Terintegrasi</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6 leading-tight">
                        Tingkatkan Kapasitas <span class="text-[#13416B]">Aparatur Daerah</span>
                    </h2>
                    <p class="text-slate-600 text-lg leading-relaxed mb-8">
                        Fasilitas pelatihan daring terstruktur sebagai sarana transfer pengetahuan yang berkelanjutan dari pusat ke daerah terkait Perencanaan Tenaga Kerja Makro, Mikro, dan IPK.
                    </p>
                    
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm text-left">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-[#13416B]/10 text-[#13416B] rounded-xl flex items-center justify-center shrink-0">
                                <i class="fas fa-award text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Sertifikat Kelulusan Resmi</h4>
                                <p class="text-sm text-slate-500 mt-1 leading-relaxed">Selesaikan rangkaian modul secara berurutan dan lulus post-test untuk mendapatkan sertifikat tanda kelulusan.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @endif

    <!-- ========================================== -->
    <!-- CTA SECTION                                -->
    <!-- ========================================== -->
    <section class="py-24 px-4 relative overflow-hidden" style="background-color: #13416B;">
        <div class="absolute inset-0 bg-blue-500/20 blur-[100px] rounded-full w-[80%] h-[80%] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        
        <div class="max-w-4xl mx-auto text-center relative z-10">
            @guest
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">Siap Memulai Perencanaan?</h2>
                <p class="text-slate-300 mb-10 max-w-2xl mx-auto text-lg">
                    Bergabunglah dengan {{ $stats['regencies'] ?? 514 }}+ daerah di seluruh Indonesia yang telah menggunakan aplikasi SIRENATA.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white font-bold rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all" style="color: #13416B;">
                        Daftar Gratis Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 font-bold rounded-full border border-white/30 text-white transition-all hover:bg-white/10">
                        Sudah Punya Akun? Masuk
                    </a>
                </div>
            @endguest
            @auth
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Selamat Datang Kembali, {{ auth()->user()->name }}!</h2>
                <p class="text-slate-300 mb-10 max-w-xl mx-auto text-lg">Lanjutkan aktivitas perencanaan ketenagakerjaan Anda dari dashboard.</p>
                <a href="{{ route('user.dashboard') }}" class="px-8 py-3.5 bg-white font-bold rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 inline-block" style="color: #13416B;">
                    Masuk ke Ruang Kerja
                </a>
            @endauth
        </div>
    </section>

    <!-- ========================================== -->
    <!-- FOOTER                                     -->
    <!-- ========================================== -->
    <footer class="py-16 px-4" style="background-color: #0b2641;">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-12 gap-10 mb-12">
                <div class="md:col-span-5">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="SIRENATA" class="h-10 w-auto brightness-0 invert">
                        <span class="text-xl font-extrabold text-white">SIRENATA</span>
                    </div>
                    <p class="text-slate-400 max-w-sm leading-relaxed mb-6 text-sm">
                        Aplikasi SIRENATA dikembangkan untuk kebutuhan penyusunan RTK Makro, RTK Mikro, dan pengukuran Indeks Pembangunan Ketenagakerjaan (IPK). Sekaligus menjadi sarana transfer pengetahuan yang berkelanjutan dari pusat ke daerah.
                    </p>
                </div>
                
                <div class="md:col-span-3">
                    <h3 class="font-bold text-white mb-4 uppercase tracking-wider text-sm">Navigasi</h3>
                    <ul class="space-y-3 text-slate-400 text-sm">
                        <li><a href="#fitur" class="hover:text-white transition-colors">Fitur Platform</a></li>
                        <li><a href="#courses" class="hover:text-white transition-colors">E-Learning</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk / Daftar</a></li>
                    </ul>
                </div>

                <div class="md:col-span-4">
                    <h3 class="font-bold text-white mb-4 uppercase tracking-wider text-sm">Pusat Bantuan</h3>
                    <ul class="space-y-3 text-slate-400 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-envelope mt-1 shrink-0"></i>
                            <a href="mailto:support@sirenata.go.id" class="hover:text-white">support@sirenata.go.id</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt mt-1 shrink-0"></i>
                            <span>Kementerian Ketenagakerjaan RI<br>Jakarta, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-slate-700/50 text-center text-slate-400 text-sm font-medium">
                <p>&copy; 2026 Kementerian Ketenagakerjaan Republik Indonesia. All rights reserved.</p>
            </div>
        </div>
    </footer>

</x-landingpage::layouts.master>