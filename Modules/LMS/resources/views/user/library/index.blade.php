<x-dashboard::layouts.dashboard title="Perpustakaan | SIRENATA">
    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto bg-slate-50/50 min-h-screen">

        {{-- Header & Breadcrumb --}}
        <div class="mb-6">
           
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mt-2">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-1 tracking-tight">Koleksi Pustaka</h1>
                    <p class="text-sm text-slate-500">Jelajahi bahan bacaan, modul, dan video pembelajaran terbaru.</p>
                </div>
                
                {{-- Info Total Data --}}
                <div class="bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm inline-flex items-center gap-3">
                    <div class="p-2 bg-[#13416B]/10 rounded-lg text-[#13416B]">
                        <i class="fas fa-books text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Total Koleksi</p>
                        <p class="text-lg font-extrabold text-slate-800 leading-none">{{ $libraries->total() }} <span class="text-xs font-medium text-slate-500">Item</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation Filter (Horizontal Pills Style) --}}
        <div class="mb-8 bg-white p-2 rounded-xl shadow-sm border border-slate-200">
            <nav class="flex space-x-1 overflow-x-auto scrollbar-hide">
                <a href="{{ route('user.library.index', ['search' => $search]) }}"
                    class="{{ !$type ? 'bg-[#13416B] text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} px-5 py-2.5 rounded-lg text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2">
                    <i class="fas fa-layer-group opacity-80"></i> Semua Koleksi
                </a>
                @foreach($libraryCategories as $libraryCategory)
                    <a href="{{ route('user.library.index', ['type' => $libraryCategory->name, 'search' => $search]) }}"
                        class="{{ $type == $libraryCategory->name ? 'bg-[#13416B] text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} px-5 py-2.5 rounded-lg text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-bookmark opacity-80"></i> {{ $libraryCategory->name }}
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- ========================================== --}}
        {{-- KONTEN UTAMA (GRID BUKU LEBIH BESAR)         --}}
        {{-- ========================================== --}}
        <div id="library-grid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6 lg:gap-8">
            @php
                $gradients = [
                    'from-[#13416B] to-[#0f3354]',
                    'from-slate-700 to-slate-900',
                    'from-blue-600 to-indigo-800',
                    'from-emerald-600 to-teal-800',
                ];
                $badgeStyles = [
                    ['text' => 'text-[#13416B]', 'bg' => 'bg-[#13416B]/10'],
                    ['text' => 'text-emerald-700', 'bg' => 'bg-emerald-100'],
                    ['text' => 'text-amber-700', 'bg' => 'bg-amber-100'],
                    ['text' => 'text-purple-700', 'bg' => 'bg-purple-100'],
                ];
            @endphp

            @forelse($libraries as $library)
                @php
                    $typeName = strtolower($library->libraryCategory->name ?? 'default');
                    $colorIdx = abs(crc32($library->libraryCategory->name ?? 'default')) % count($gradients);
                    $gradient = $gradients[$colorIdx];
                    $badge = $badgeStyles[$colorIdx];
                    $isVideo = str_contains($typeName, 'video');
                    $isPeraturan = str_contains($typeName, 'peraturan');
                    $buttonLabel = $isVideo ? 'Tonton' : 'Baca';
                @endphp

                <!-- Card Style Buku -->
                <div class="group bg-white rounded-2xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden hover:shadow-2xl hover:shadow-[#13416B]/10 transition-all duration-300 flex flex-col hover:-translate-y-1.5">
                    
                    {{-- Cover Area (Rasio Potrait Buku yang Lebar) --}}
                    <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
                        @if($library->cover_image)
                            <img src="{{ Storage::url($library->cover_image) }}" alt="{{ $library->title }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center p-6 text-center shadow-inner relative overflow-hidden">
                                <!-- Efek dekoratif bayangan di cover kosong -->
                                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                                <div class="absolute -left-6 -top-6 w-24 h-24 bg-black/10 rounded-full blur-xl"></div>
                                
                                @if($isVideo)
                                    <i class="fas fa-play-circle text-5xl sm:text-7xl text-white/80 drop-shadow-lg transition-transform group-hover:scale-110 duration-300"></i>
                                @elseif($isPeraturan)
                                    <i class="fas fa-gavel text-5xl sm:text-7xl text-white/80 drop-shadow-lg transition-transform group-hover:scale-110 duration-300"></i>
                                @else
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-book-open text-4xl sm:text-6xl text-white/80 drop-shadow-lg mb-4 transition-transform group-hover:scale-110 duration-300"></i>
                                        <p class="text-white/95 text-xs sm:text-sm font-bold uppercase tracking-widest line-clamp-3 leading-tight px-4 drop-shadow-md">
                                            {{ $library->title }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Hover Overlay (Hanya Desktop) --}}
                        <div class="hidden lg:flex absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 items-center justify-center backdrop-blur-[2px]">
                            <button x-data @click="$dispatch('open-modal', 'library-modal-{{ $library->id }}')" class="bg-white text-[#13416B] font-bold text-sm px-6 py-3 rounded-full shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-slate-50 hover:scale-105">
                                <i class="fas {{ $isVideo ? 'fa-play' : 'fa-book-reader' }} mr-2"></i> Buka Koleksi
                            </button>
                        </div>

                        {{-- Kategori Badge Absolute --}}
                        <div class="absolute top-4 left-4">
                            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider {{ $badge['text'] }} {{ $badge['bg'] }} px-2.5 py-1.5 rounded-lg shadow-sm backdrop-blur-md">
                                {{ $library->libraryCategory->name ?? 'Materi' }}
                            </span>
                        </div>
                    </div>

                    {{-- Informasi Buku Area --}}
                    <div class="p-4 sm:p-6 flex flex-col flex-grow">
                        <h3 class="font-extrabold text-slate-800 text-base sm:text-lg leading-snug line-clamp-2 mb-2 group-hover:text-[#13416B] transition-colors" title="{{ $library->title }}">
                            {{ $library->title }}
                        </h3>
                        
                        @if($library->description)
                            <p class="text-sm text-slate-500 line-clamp-2 mb-5 leading-relaxed">
                                {{ $library->description }}
                            </p>
                        @endif

                        <div class="mt-auto pt-4 border-t border-slate-50">
                            <button x-data @click="$dispatch('open-modal', 'library-modal-{{ $library->id }}')"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl group-hover:bg-[#13416B] group-hover:text-white group-hover:border-[#13416B] text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2">
                                {{ $buttonLabel }} <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Modal Preview --}}
                <x-modal name="library-modal-{{ $library->id }}" title="{{ $library->title }}" maxWidth="sm:max-w-5xl">
                    <div class="flex flex-col lg:flex-row bg-slate-50">
                        {{-- Preview Kiri --}}
                        <div class="flex-1 bg-slate-900 min-h-[300px] sm:min-h-[550px] relative">
                            @if($library->file_path)
                                <iframe src="{{ Storage::url($library->file_path) }}"
                                    class="w-full h-[300px] sm:h-[550px] border-0" frameborder="0"></iframe>
                            @elseif($library->video_path)
                                <video src="{{ Storage::url($library->video_path) }}" class="w-full h-full max-h-[550px] object-contain bg-black" controls></video>
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
                                <iframe src="{{ $videoUrl }}" class="w-full h-[300px] sm:h-[550px] border-0" frameborder="0" allowfullscreen></iframe>
                            @elseif($library->external_link)
                                <iframe src="{{ $library->external_link }}" class="w-full h-[300px] sm:h-[550px] border-0 bg-white" frameborder="0" allowfullscreen title="Link Preview"></iframe>
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
                <div class="col-span-2 sm:col-span-2 md:col-span-3 xl:col-span-4 text-center py-24 bg-white rounded-2xl border border-slate-200 border-dashed">
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