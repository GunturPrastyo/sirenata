<x-dashboard::layouts.dashboard title="Perpustakaan | SIRENATA">
    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto bg-slate-50 min-h-screen">

        {{-- ========================================== --}}
        {{-- HEADER PERPUSTAKAAN DENGAN ILUSTRASI PAPAN --}}
        {{-- ========================================== --}}
        <div class="relative bg-[#13416B] rounded-2xl p-6 sm:p-8 lg:p-10 mb-8 sm:mb-10 flex flex-col sm:flex-row items-center justify-between gap-8 sm:gap-4 border border-blue-900/20 shadow-lg mt-2 sm:mt-0 overflow-hidden">
            
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

                <!-- Semburat Cahaya Halus -->
                <div class="absolute left-0 top-0 w-2/3 h-full bg-gradient-to-r from-[#13416B] via-[#13416B]/80 to-transparent z-10"></div>
            </div>
            
            <!-- Sisi Kiri: Teks Utama -->
            <div class="relative z-20 w-full sm:w-[55%] lg:w-[60%] order-1 text-left">
             
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight mb-4">
                    Koleksi Pustaka & Referensi
                </h1>
                <p class="text-sm md:text-sm sm:text-base text-blue-100/80 leading-relaxed max-w-xl mx-0 font-medium">
                    Jelajahi berbagai modul pembelajaran, bahan bacaan, dokumen peraturan, hingga video interaktif yang dirancang khusus untuk meningkatkan kompetensi Anda.
                </p>

                <!-- Info Statistik Mobile (Kalem & Diperbagus) -->
                <div class="sm:hidden mt-5 inline-flex items-center gap-2.5 px-4 py-2.5 bg-white/10 backdrop-blur-md rounded-xl border border-white/20 shadow-sm">
                    <span class="text-white text-xs font-bold tracking-wide">{{ $libraries->total() ?? 0 }} Koleksi Tersedia</span>
                </div>
            </div>

            <!-- Sisi Kanan: Ilustrasi Orang & Papan Pendaftar (Tampil di Desktop & Tablet) -->
            <div class="hidden sm:flex relative z-20 w-full sm:w-[45%] lg:w-[35%] flex-col items-center justify-end order-2 mt-8 sm:mt-0">

                <!-- Lingkaran Kuning di belakang Ilustrasi (Ukurannya diperkecil sedikit) -->
                <div class="absolute bottom-8 lg:bottom-12 w-40 h-40 lg:w-52 lg:h-52 bg-amber-400 rounded-full z-0 opacity-90 shadow-inner"></div>

                <!-- Gambar Ilustrasi Orang -->
                <img src="{{ asset('images/ilustrasi1.webp') }}" 
                     alt="Ilustrasi Menunjuk" 
                     class="w-52 sm:w-64 lg:w-72 -mb-8 sm:-mb-10 lg:-mb-11.5 relative z-20 drop-shadow-[0_15px_25px_rgba(0,0,0,0.4)] pointer-events-none object-contain transition-transform duration-500 hover:scale-105"
                     onerror="this.style.display='none'"> 
                     
                <!-- Papan "Card" Total Koleksi -->
                <div class="relative z-10 bg-white rounded-2xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.4)] pt-8 pb-5 px-6 sm:px-8 w-full max-w-[240px] sm:max-w-[280px] text-center mx-auto border-b-4 border-amber-400 ring-1 ring-slate-900/5">
                    
                    <!-- Pita Label di atas -->
                    <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-[9px] sm:text-[10px] font-bold uppercase tracking-widest px-4 py-1.5 rounded-full shadow-md whitespace-nowrap">
                        Statistik Update
                    </div>
                    
                    <!-- Judul Total Koleksi -->
                    <h3 class="text-xs sm:text-sm font-extrabold text-slate-400 uppercase tracking-widest mb-1">
                        Total Koleksi
                    </h3>
                    
                    <!-- Angka & Teks -->
                    <p class="text-[11px] sm:text-xs font-bold text-slate-800 m-0 flex items-center justify-center gap-1.5">
                        <span class="text-xl sm:text-2xl font-black text-[#13416B] leading-none">{{ $libraries->total() ?? 0 }}</span>
                        <span class="pt-1 uppercase">Tersedia</span>
                    </p>
                </div>
            </div>
            
        </div>

        {{-- ========================================== --}}
        {{-- NAVIGASI FILTER KATEGORI (PILLS STYLE)     --}}
        {{-- ========================================== --}}
        <div class="mb-8">
            <h2 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                <i class="fas fa-filter text-slate-400"></i> Filter Kategori
            </h2>
            <nav class="flex flex-wrap gap-2">
                {{-- Tombol "Semua Koleksi" --}}
                <a href="{{ route('user.library.index', ['search' => $search]) }}"
                    class="px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 border flex items-center gap-2 {{ !$type ? 'bg-[#13416B] text-white border-[#13416B] shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
                    <span>Semua Koleksi</span>
                    <span class="{{ !$type ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }} px-1.5 py-0.5 rounded-md text-[10px]">{{ \Modules\LMS\Models\Library::count() }}</span>
                </a>
                
                {{-- Loop Kategori --}}
                @foreach($libraryCategories as $libraryCategory)
                    @php
                        $countPerCategory = \Modules\LMS\Models\Library::where('library_category_id', $libraryCategory->id)->count();
                    @endphp
                    <a href="{{ route('user.library.index', ['type' => $libraryCategory->name, 'search' => $search]) }}"
                        class="px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 border flex items-center gap-2 {{ $type == $libraryCategory->name ? 'bg-[#13416B] text-white border-[#13416B] shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50 hover:border-slate-300' }}">
                        <span>{{ $libraryCategory->name }}</span>
                        <span class="{{ $type == $libraryCategory->name ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }} px-1.5 py-0.5 rounded-md text-[10px]">{{ $countPerCategory }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- ========================================== --}}
        {{-- KONTEN UTAMA                               --}}
        {{-- ========================================== --}}
        <!-- Grid diatur maksimal 3 kolom (lg:grid-cols-3) agar card lebih lebar di desktop -->
        <div id="library-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @php
                $gradients = [
                    'from-[#13416B] to-[#0f3354]',
                    'from-slate-700 to-slate-900',
                    'from-indigo-600 to-indigo-800',
                    'from-emerald-600 to-teal-800',
                ];
            @endphp

            @forelse($libraries as $library)
                @php
                    $typeName = strtolower($library->libraryCategory->name ?? 'default');
                    $colorIdx = abs(crc32($library->libraryCategory->name ?? 'default')) % count($gradients);
                    $gradient = $gradients[$colorIdx];
                    
                    $isVideo = !empty($library->video_path) || str_contains($library->external_link ?? '', 'youtube') || str_contains($library->external_link ?? '', 'youtu.be');
                    $isPeraturan = str_contains($typeName, 'peraturan');
                    $isDoc = !empty($library->file_path);

                    if ($isVideo) {
                        $fallbackIcon = 'fa-play-circle';
                        $buttonLabel = 'Tonton Video';
                    } elseif ($isPeraturan) {
                        $fallbackIcon = 'fa-gavel';
                        $buttonLabel = 'Baca Dokumen';
                    } elseif ($isDoc) {
                        $fallbackIcon = 'fa-file-pdf';
                        $buttonLabel = 'Baca File';
                    } elseif (!empty($library->external_link)) {
                        $fallbackIcon = 'fa-external-link-alt';
                        $buttonLabel = 'Buka Tautan';
                    } else {
                        $fallbackIcon = 'fa-book-open';
                        $buttonLabel = 'Buka Pustaka';
                    }
                @endphp

                <!-- Card Style Buku -->
                <div class="group flex flex-col bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-xl hover:border-[#13416B]/30 transition-all duration-300 hover:-translate-y-1">
                    
                    {{-- Cover Area (Dikembalikan ke aspect-[3/4] agar lebih tinggi proporsional) --}}
                    <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
                        @if($library->cover_image)
                            <img src="{{ Storage::url($library->cover_image) }}" alt="{{ $library->title }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center p-3 sm:p-6 text-center relative overflow-hidden">
                                <div class="absolute -right-4 -bottom-4 sm:-right-6 sm:-bottom-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                                <div class="absolute -left-4 -top-4 sm:-left-6 sm:-top-6 w-16 h-16 bg-black/10 rounded-full blur-xl"></div>
                                <i class="fas {{ $fallbackIcon }} text-6xl sm:text-7xl text-white/80 drop-shadow-md transition-transform group-hover:scale-110 duration-300 relative z-10"></i>
                            </div>
                        @endif

                        {{-- Hover Overlay (Hanya Desktop) --}}
                        <div class="hidden lg:flex absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 items-center justify-center backdrop-blur-[2px]">
                            <button x-data @click="$dispatch('open-modal', 'library-modal-{{ $library->id }}')" class="bg-white text-[#13416B] font-bold text-sm px-6 py-3 rounded-full shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-slate-50 hover:scale-105">
                                <i class="fas {{ $isVideo ? 'fa-play' : 'fa-book-reader' }} mr-2"></i> Buka Koleksi
                            </button>
                        </div>

                        {{-- Kategori Badge Absolute --}}
                        <div class="absolute top-3 left-3 z-20">
                            <span class="px-2.5 py-1 text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-800 bg-white/95 backdrop-blur-sm rounded shadow-sm border border-slate-100">
                                {{ $library->libraryCategory->name ?? 'Materi' }}
                            </span>
                        </div>
                    </div>

                    {{-- Informasi Buku Area --}}
                    <div class="p-4 sm:p-5 flex flex-col flex-1 bg-white">
                        <h3 class="font-bold text-slate-800 text-sm sm:text-base leading-snug line-clamp-2 mb-2 group-hover:text-[#13416B] transition-colors" title="{{ $library->title }}">
                            {{ $library->title }}
                        </h3>

                        {{-- Action Button Dibuat Lebar Penuh --}}
                        <div class="mt-auto pt-4 border-t border-slate-100">
                            <button x-data @click="$dispatch('open-modal', 'library-modal-{{ $library->id }}')"
                                class="w-full py-2.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl group-hover:bg-[#13416B] group-hover:text-white group-hover:border-[#13416B] text-[11px] sm:text-xs font-bold transition-all duration-300 flex items-center justify-center gap-2">
                                <span>{{ $buttonLabel }}</span>
                                <i class="fas fa-arrow-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Modal Preview --}}
                <x-modal name="library-modal-{{ $library->id }}" title="{{ $library->title }}" maxWidth="sm:max-w-5xl">
                    <div class="flex flex-col lg:flex-row bg-slate-50">
                        {{-- Preview Kiri --}}
                        <div class="flex-1 bg-slate-900 min-h-[300px] sm:min-h-[550px] relative overflow-hidden">
                            @if($library->file_path)
                                <iframe src="{{ Storage::url($library->file_path) }}"
                                    class="absolute inset-0 w-full h-full border-0" frameborder="0"></iframe>
                            @elseif($library->video_path)
                                <video src="{{ Storage::url($library->video_path) }}" class="absolute inset-0 w-full h-full object-contain bg-black" controls></video>
                            @elseif($library->external_link && (str_contains($library->external_link, 'youtube.com') || str_contains($library->external_link, 'youtu.be')))
                                @php
                                    $videoUrl = $library->external_link;
                                    if (str_contains($videoUrl, 'youtube.com/watch')) {
                                        $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                                        $videoUrl = preg_replace('/&.*/', '', $videoUrl);
                                    } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                        $videoUrl = str_replace('youtu.be/', 'youtube.com/embed/', $videoUrl);
                                    }
                                @endphp
                                <iframe src="{{ $videoUrl }}" class="absolute inset-0 w-full h-full border-0" frameborder="0" allowfullscreen></iframe>
                            @elseif($library->external_link)
                                <iframe src="{{ $library->external_link }}" class="absolute inset-0 w-full h-full border-0 bg-white" frameborder="0" allowfullscreen title="Link Preview"></iframe>
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-slate-400 flex-col">
                                    <i class="fas fa-eye-slash text-6xl mb-4 opacity-50"></i>
                                    <p class="text-base font-medium">Pratinjau tidak tersedia.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Sidebar Kanan Detail --}}
                        <div class="lg:w-80 shrink-0 p-6 sm:p-8 bg-white border-l border-slate-200 flex flex-col h-full">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-5 border-b border-slate-100 pb-3">Informasi Pustaka</h4>
                            
                            <div class="space-y-5 mb-6">
                                <div>
                                    <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wide">Kategori</span>
                                    <p class="text-sm font-extrabold text-[#13416B] mt-0.5">{{ $library->libraryCategory->name ?? '-' }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wide">Judul</span>
                                    <p class="text-base font-bold text-slate-800 leading-snug mt-0.5">{{ $library->title }}</p>
                                </div>

                                @if($library->description)
                                    <div>
                                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-wide">Deskripsi Singkat</span>
                                        <p class="text-sm text-slate-600 leading-relaxed mt-1">{{ $library->description }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-auto space-y-3 pt-6 border-t border-slate-100">
                                @if($library->external_link)
                                    <a href="{{ $library->external_link }}" target="_blank"
                                        class="flex items-center gap-2 w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-100 hover:text-slate-900 text-sm font-bold transition-colors justify-center shadow-sm">
                                        <i class="fas fa-external-link-alt"></i> Buka Tautan Asli
                                    </a>
                                @endif

                                @if($library->file_path)
                                    <a href="{{ Storage::url($library->file_path) }}" target="_blank" download
                                        class="flex items-center gap-2 w-full px-4 py-3 bg-[#13416B] text-white rounded-xl hover:bg-[#0f3354] text-sm font-bold transition-colors justify-center shadow-sm">
                                        <i class="fas fa-download"></i> Unduh File PDF
                                    </a>
                                @endif

                                @if($library->video_path)
                                    <a href="{{ Storage::url($library->video_path) }}" target="_blank" download
                                        class="flex items-center gap-2 w-full px-4 py-3 bg-amber-500 text-slate-900 rounded-xl hover:bg-amber-600 text-sm font-bold transition-colors justify-center shadow-sm">
                                        <i class="fas fa-video"></i> Unduh File Video
                                    </a>
                                @endif
                                
                                <button type="button" x-data @click="$dispatch('close-modal', 'library-modal-{{ $library->id }}')" class="w-full text-center text-xs font-bold text-slate-400 hover:text-slate-600 mt-3 py-2 transition-colors">
                                    Tutup Pratinjau
                                </button>
                            </div>
                        </div>
                    </div>
                </x-modal>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 px-4 bg-white rounded-2xl border border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 text-slate-300">
                        <i class="fas fa-search text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Koleksi Kosong</h3>
                    <p class="text-sm text-slate-500 max-w-sm text-center">Materi perpustakaan dengan filter yang Anda cari belum tersedia.</p>
                    @if($search || $type)
                        <a href="{{ route('user.library.index') }}"
                            class="mt-6 inline-flex items-center px-6 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 text-xs font-bold transition-colors shadow-sm">
                            <i class="fas fa-sync-alt mr-2"></i> Bersihkan Filter
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($libraries->hasPages())
            <div class="mt-10 border-t border-slate-200 pt-8 flex justify-center">
                {{ $libraries->appends(['type' => $type, 'search' => $search])->links() }}
            </div>
        @endif
    </div>
</x-dashboard::layouts.dashboard>