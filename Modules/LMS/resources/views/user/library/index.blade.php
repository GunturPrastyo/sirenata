<x-dashboard::layouts.dashboard title="Perpustakaan | SIRENATA">
    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto bg-slate-50/50 min-h-screen">

        {{-- ========================================== --}}
        {{-- HEADER PERPUSTAKAAN DENGAN ILUSTRASI PAPAN --}}
        {{-- ========================================== --}}
        <div class="relative bg-gradient-to-br from-[#13416B] via-[#184A78] to-[#0f3354] rounded-lg p-6 sm:p-8 lg:p-10 mb-6 sm:mb-8 flex flex-col sm:flex-row items-center justify-between gap-8 sm:gap-10 border border-blue-900/50 mt-2 sm:mt-0">
            
            <!-- Efek Dekoratif Background -->
            <div class="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none">
                <div class="absolute -right-20 -top-20 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl mix-blend-overlay"></div>
                <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-emerald-400/10 rounded-full blur-2xl mix-blend-overlay"></div>
            </div>
            
            <!-- Sisi Kiri: Teks Utama -->
            <div class="relative z-10 w-full sm:w-3/5 lg:w-2/3 order-1 text-left">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white/10 backdrop-blur-md rounded-lg text-[10px] sm:text-xs font-bold uppercase tracking-widest text-blue-100 border border-white/20 mb-4 shadow-sm">
                    Pusat Sumber Belajar
                </span>
                <h1 class="text-3xl sm:text-3xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight mb-3 sm:mb-4 drop-shadow-md">
                    Koleksi Pustaka & Referensi
                </h1>
                <p class="text-sm sm:text-base text-blue-100/90 leading-relaxed max-w-xl mx-0">
                    Jelajahi berbagai modul pembelajaran, bahan bacaan, dokumen peraturan, hingga video interaktif yang dirancang khusus untuk meningkatkan kompetensi Anda.
                </p>

                <!-- Info Statistik Mobile (HANYA TAMPIL DI MOBILE) -->
                <div class="sm:hidden mt-5 inline-flex items-center gap-2.5 px-4 py-2.5 bg-white/10 backdrop-blur-md rounded-xl border border-white/20 shadow-sm">
                    <span class="text-white text-xs font-bold">{{ $libraries->total() ?? 0 }} Koleksi Tersedia</span>
                </div>
            </div>

            <!-- Sisi Kanan: Ilustrasi Orang & Papan Pendaftar -->
            <div class="hidden sm:flex relative z-20 w-full sm:w-2/5 lg:w-1/3 flex-col items-center justify-end order-2 mt-8 sm:mt-0">

                <!-- Lingkaran Kuning di belakang Ilustrasi -->
                <div class="absolute bottom-8 lg:bottom-12 w-48 h-48 lg:w-60 lg:h-60 bg-amber-400 rounded-full z-0 opacity-95 shadow-inner"></div>

                <!-- Gambar Ilustrasi Orang -->
                <img src="{{ asset('images/ilustrasi1.webp') }}" 
                     alt="Ilustrasi Menunjuk" 
                     class="w-52 sm:w-64 lg:w-72 -mb-8 sm:-mb-10 lg:-mb-11 relative z-20 drop-shadow-[0_15px_15px_rgba(0,0,0,0.4)] pointer-events-none object-contain transition-transform duration-500 hover:scale-105"
                     onerror="this.style.display='none'"> 
                     
                <!-- Papan "Card" Total Koleksi -->
                <div class="relative z-10 bg-white rounded-3xl shadow-[0_20px_50px_-10px_rgba(0,0,0,0.5)] pt-6 sm:pt-8 pb-5 px-6 sm:px-8 w-full max-w-[240px] sm:max-w-[280px] text-center mx-auto border-r-[6px] border-b-[2px] border-amber-400">
                    
                    <!-- Pita Label di atas -->
                    <div class="absolute -top-3 sm:-top-4 left-1/2 transform -translate-x-1/2 bg-[#13416B] text-white text-[9px] sm:text-[10px] font-black uppercase tracking-widest px-5 py-1.5 sm:py-2 rounded-lg shadow-md border border-blue-400/30 whitespace-nowrap">
                        Statistik Update
                    </div>
                    
                    <!-- Judul Total Koleksi -->
                    <h3 class="text-sm sm:text-base font-black text-slate-800 uppercase mt-1 mb-2">
                        Total Koleksi
                    </h3>
                    
                    <!-- Angka & Teks Digabung Jadi Sebaris Kecil -->
                    <p class="text-[11px] sm:text-xs font-bold text-slate-500 m-0">
                        {{ $libraries->total() ?? 0 }} Koleksi Tersedia
                    </p>
                </div>
            </div>
            
        </div>

        {{-- Navigation Filter (Horizontal Pills Style) dengan Penghitung Jumlah Kategori --}}
        <div class="mb-6 sm:mb-8 bg-white p-2 rounded-xl shadow-sm border border-slate-200">
            <nav class="flex space-x-1 overflow-x-auto scrollbar-hide">
                {{-- Tombol "Semua Koleksi" beserta total seluruh item --}}
                <a href="{{ route('user.library.index', ['search' => $search]) }}"
                    class="{{ !$type ? 'bg-[#13416B] text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} px-4 sm:px-5 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2">
                    Semua Koleksi ({{ \Modules\LMS\Models\Library::count() }})
                </a>
                
                {{-- Loop Kategori beserta jumlah item di dalamnya --}}
                @foreach($libraryCategories as $libraryCategory)
                    @php
                        $countPerCategory = \Modules\LMS\Models\Library::where('library_category_id', $libraryCategory->id)->count();
                    @endphp
                    <a href="{{ route('user.library.index', ['type' => $libraryCategory->name, 'search' => $search]) }}"
                        class="{{ $type == $libraryCategory->name ? 'bg-[#13416B] text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} px-4 sm:px-5 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2">
                        {{ $libraryCategory->name }} ({{ $countPerCategory }})
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- ========================================== --}}
        {{-- KONTEN UTAMA                               --}}
        {{-- ========================================== --}}
        <div id="library-grid" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 lg:gap-8">
            @php
                $gradients = [
                    'from-[#13416B] to-[#0f3354]',
                    'from-slate-700 to-slate-900',
                    'from-blue-600 to-indigo-800',
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
                        $buttonLabel = 'Tonton';
                    } elseif ($isPeraturan) {
                        $fallbackIcon = 'fa-gavel';
                        $buttonLabel = 'Baca';
                    } elseif ($isDoc) {
                        $fallbackIcon = 'fa-file-pdf';
                        $buttonLabel = 'Baca';
                    } elseif (!empty($library->external_link)) {
                        $fallbackIcon = 'fa-external-link-alt';
                        $buttonLabel = 'Buka Tautan';
                    } else {
                        $fallbackIcon = 'fa-book-open';
                        $buttonLabel = 'Baca';
                    }
                @endphp

                <!-- Card Style Buku -->
                <div class="group bg-white rounded-xl sm:rounded-2xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden hover:shadow-2xl hover:shadow-[#13416B]/10 transition-all duration-300 flex flex-col hover:-translate-y-1">
                    
                    {{-- Cover Area --}}
                    <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
                        @if($library->cover_image)
                            <img src="{{ Storage::url($library->cover_image) }}" alt="{{ $library->title }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center p-3 sm:p-6 text-center shadow-inner relative overflow-hidden">
                                <div class="absolute -right-4 -bottom-4 sm:-right-6 sm:-bottom-6 w-24 h-24 sm:w-32 sm:h-32 bg-white/10 rounded-full blur-xl sm:blur-2xl"></div>
                                <div class="absolute -left-4 -top-4 sm:-left-6 sm:-top-6 w-16 h-16 sm:w-24 sm:h-24 bg-black/10 rounded-full blur-lg sm:blur-xl"></div>
                                
                                <i class="fas {{ $fallbackIcon }} text-5xl sm:text-7xl text-white/80 drop-shadow-lg transition-transform group-hover:scale-110 duration-300 relative z-10"></i>
                            </div>
                        @endif

                        {{-- Hover Overlay (Hanya Desktop) --}}
                        <div class="hidden lg:flex absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 items-center justify-center backdrop-blur-[2px]">
                            <button x-data @click="$dispatch('open-modal', 'library-modal-{{ $library->id }}')" class="bg-white text-[#13416B] font-bold text-sm px-6 py-3 rounded-full shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-slate-50 hover:scale-105">
                                <i class="fas {{ $isVideo ? 'fa-play' : 'fa-book-reader' }} mr-2"></i> Buka Koleksi
                            </button>
                        </div>

                        {{-- Kategori Badge Absolute --}}
                        <div class="absolute top-2 left-2 sm:top-4 sm:left-4 z-20">
                            <span class="text-[8px] sm:text-xs font-extrabold uppercase tracking-wider text-slate-800 bg-amber-400 px-2 py-1 sm:px-2.5 sm:py-1.5 rounded sm:rounded-lg shadow-md border border-amber-500/50">
                                {{ $library->libraryCategory->name ?? 'Materi' }}
                            </span>
                        </div>
                    </div>

                    {{-- Informasi Buku Area --}}
                    <div class="p-2.5 sm:p-5 flex flex-col flex-grow z-10 bg-white">
                        <h3 class="font-bold sm:font-extrabold text-slate-800 text-xs sm:text-lg leading-tight sm:leading-snug line-clamp-2 mb-3 group-hover:text-[#13416B] transition-colors" title="{{ $library->title }}">
                            {{ $library->title }}
                        </h3>

                        <div class="mt-auto pt-2.5 sm:pt-3 border-t border-slate-50">
                            <button x-data @click="$dispatch('open-modal', 'library-modal-{{ $library->id }}')"
                                class="w-full px-2 py-1.5 sm:px-4 sm:py-2.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-lg sm:rounded-xl group-hover:bg-[#13416B] group-hover:text-white group-hover:border-[#13416B] text-[10px] sm:text-sm font-bold transition-all duration-300 flex items-center justify-center">
                                {{ $buttonLabel }}
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
                <div class="col-span-full text-center py-24 bg-white rounded-2xl border border-slate-200 border-dashed">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100">
                        <i class="fas fa-books text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-800">Koleksi Kosong</h3>
                    <p class="mt-2 text-base text-slate-500 max-w-md mx-auto">Materi perpustakaan dengan filter yang Anda cari belum tersedia.</p>
                    @if($search || $type)
                        <a href="{{ route('user.library.index') }}"
                            class="mt-6 inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 text-sm font-bold transition-colors shadow-sm">
                            <i class="fas fa-sync-alt mr-2"></i> Bersihkan Filter
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($libraries->hasPages())
            <div class="mt-10 border-t border-slate-200 pt-8">
                {{ $libraries->appends(['type' => $type, 'search' => $search])->links() }}
            </div>
        @endif
    </div>
</x-dashboard::layouts.dashboard>