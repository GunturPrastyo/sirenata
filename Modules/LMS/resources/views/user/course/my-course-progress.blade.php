<x-dashboard::layouts.dashboard title="Kursus Saya - Belum Selesai | SIRENATA">
    <div class="p-4 sm:p-6 lg:p-8 bg-slate-50 min-h-screen">

     {{-- ========================================== --}}
        {{-- HEADER KURSUS SAYA DENGAN ILUSTRASI GRUP   --}}
        {{-- ========================================== --}}
        <div class="relative bg-[#13416B] rounded-2xl p-6 sm:p-8 lg:p-10 mb-6 sm:mb-8 flex items-center justify-between border border-blue-900/20 shadow-lg overflow-hidden min-h-[220px] sm:min-h-[280px] lg:min-h-[320px]">
            
            <!-- Efek Dekoratif Bubbles Geometris Profesional -->
            <div class="absolute inset-0 pointer-events-none z-0">
                <!-- Pola titik halus dasar -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.05)_1.5px,transparent_1.5px)] [background-size:24px_24px]"></div>
                
                <!-- Bubble 1: Cincin Besar Kanan Atas -->
                <div class="absolute -top-24 -right-16 w-80 h-80 border-[30px] border-white/5 rounded-full"></div>
                <!-- Bubble 2: Lingkaran Solid Transparan Kanan Bawah -->
                <div class="absolute -bottom-20 right-[10%] w-64 h-64 bg-white/5 rounded-full"></div>
                <!-- Bubble 3: Cincin Aksen Kuning -->
                <div class="absolute top-[15%] right-[38%] w-24 h-24 border-[8px] border-amber-400/20 rounded-full"></div>
                <!-- Bubble 4: Titik Biru Muda -->
                <div class="absolute bottom-[30%] right-[45%] w-8 h-8 bg-blue-400/20 rounded-full"></div>
                <!-- Bubble 5: Lingkaran Kecil Kiri -->
                <div class="absolute top-[20%] left-[45%] w-12 h-12 bg-white/5 rounded-full"></div>

                <!-- Semburat Cahaya Halus agar teks tetap kontras -->
                <div class="absolute left-0 top-0 w-2/3 h-full bg-gradient-to-r from-[#13416B] via-[#13416B]/80 to-transparent z-10"></div>
            </div>

            <!-- Sisi Kiri: Teks Utama -->
            <div class="relative z-20 w-full sm:w-[60%] lg:w-[60%] text-left">
              
                
                <!-- Judul Diperlebar -->
                <h1 class="text-xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight mb-4">
                    Pelatihan & Pengembangan Kompetensi
                </h1>
                
                <!-- Deskripsi Diperpanjang -->
                <p class="text-sm md:text-sm lg:text-base text-blue-100/90 leading-relaxed max-w-xl lg:max-w-2xl font-medium">
                    Selesaikan modul pelatihan Anda, ikuti evaluasi dengan baik, dan raih sertifikat kelulusan untuk mendukung perjalanan karier serta profesionalisme Anda.
                </p>

                <!-- Info Statistik Mobile -->
                <div class="sm:hidden mt-5 inline-flex items-center gap-2.5 px-4 py-2.5 bg-white/10 backdrop-blur-md rounded-xl border border-white/20 shadow-sm">
                    <span class="text-white text-xs font-bold tracking-wide">{{ $meta['total'] ?? 0 }} Kursus Terdaftar</span>
                </div>
            </div>

            <!-- Sisi Kanan: Ilustrasi Pegawai Kemnaker -->
            <div class="hidden sm:flex absolute bottom-0 right-0 lg:right-5 z-10 w-[45%] lg:w-[35%] h-[90%] lg:h-[95%] pointer-events-none justify-end items-end">
                <!-- Gambar Ilustrasi -->
                <img src="{{ asset('images/pegawai_kemnaker.webp') }}" 
                     alt="Pegawai Kemnaker" 
                     class="w-full h-full object-contain object-bottom drop-shadow-[0_15px_25px_rgba(0,0,0,0.3)] relative z-20"
                     onerror="this.style.display='none'">
            </div>
        </div>
        <div>
            <div class="mb-6 sm:mb-8 border-b border-slate-200">
                <nav class="flex space-x-6 overflow-x-auto pb-[-1px]">
                    <a href="{{ route('user.course.my-course') }}"
                        class="border-b-2 py-3 px-1 text-sm whitespace-nowrap transition-colors border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium">
                        <span>Semua</span>
                    </a>
                    <a href="{{ route('user.course.my-course.progress') }}"
                        class="border-b-2 py-3 px-1 text-sm whitespace-nowrap transition-colors border-indigo-600 text-indigo-600 font-bold">
                        <span>Belum Selesai ({{ $meta['total'] ?? 0 }})</span>
                    </a>
                    <a href="{{ route('user.course.my-course.finish') }}"
                        class="border-b-2 py-3 px-1 text-sm whitespace-nowrap transition-colors border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium">
                        <span>Selesai</span>
                    </a>
                </nav>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                @forelse ($courses as $course)
                    <a href="{{ route('user.course.my-course.detail', $course->slug) }}"
                        class="group flex flex-col bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-[#13416B]/30 hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                        <div class="relative h-44 sm:h-48 overflow-hidden bg-slate-100">
                            @if (!empty($course->thumbnail_url))
                                <img src="{{ $course->thumbnail_url }}"
                                    alt="{{ $course->course_name ?? $course->name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-slate-300"></i>
                                </div>
                            @endif

                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-800 bg-white/90 backdrop-blur-sm rounded shadow-sm">
                                    {{ $course->category->name ?? 'Umum' }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="text-base font-bold text-slate-800 leading-snug mb-2 group-hover:text-[#13416B] transition-colors line-clamp-2">
                                {{ $course->course_name ?? $course->name }}
                            </h3>

                            <div class="flex items-center gap-3 mb-3 text-xs font-medium text-slate-500">
                                <span class="flex items-center gap-1.5 bg-slate-50 px-2 py-1 rounded border border-slate-100">
                                    <i class="fas fa-layer-group text-slate-400"></i> {{ $course->total_modul ?? 0 }} Modul
                                </span>
                                <span class="flex items-center gap-1.5 bg-slate-50 px-2 py-1 rounded border border-slate-100">
                                    <i class="fas fa-file-alt text-slate-400"></i> {{ $course->total_materi ?? 0 }} Materi
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 mb-5 line-clamp-2 leading-relaxed flex-1">
                                {{ $course->description ?? 'Tidak ada deskripsi singkat yang tersedia untuk kursus ini.' }}
                            </p>

                            <div class="mt-auto pt-4 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-2 text-[11px] font-bold uppercase tracking-wider">
                                    <span class="text-slate-500">Progress</span>
                                    <span class="text-[#13416B]">{{ $course->progress ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mb-4">
                                    <div class="bg-[#13416B] h-full rounded-full transition-all duration-700"
                                        style="width: {{ $course->progress ?? 0 }}%"></div>
                                </div>

                                <div class="w-full py-2.5 text-xs font-bold text-center rounded-xl transition-colors bg-slate-50 text-slate-700 border border-slate-200 group-hover:bg-[#13416B] group-hover:text-white group-hover:border-[#13416B]">
                                    Lanjutkan Modul <i class="fas fa-arrow-right ml-1"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 px-4 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 text-slate-300">
                            <i class="fas fa-check-double text-2xl"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Semua Kursus Selesai!</h3>
                        <p class="text-sm text-slate-500 max-w-sm">Anda tidak memiliki kursus yang sedang berjalan.</p>
                    </div>
                @endforelse
            </div>

            @if (!empty($courses) && count($courses) > 0)
                <div class="mt-8 flex justify-center">
                    <x-api-pagination :meta="$meta" />
                </div>
            @endif
        </div>
    </div>
</x-dashboard::layouts.dashboard>