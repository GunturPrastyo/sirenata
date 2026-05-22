<x-dashboard::layouts.dashboard title="Perpustakaan | SIRENATA">
    <div class="p-2 sm:p-6">

        <x-breadcrumb :items="[['label' => 'Perpustakaan']]" />

        <div class="mb-4 sm:mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-2 sm:space-x-4 overflow-x-auto pb-2 scrollbar-hide -mx-2 px-2">
                    <a href="{{ route('user.library.index', ['search' => $search]) }}"
                        class="{{ !$type ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' : 'border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium' }} py-2 sm:py-3 px-1 text-xs sm:text-sm transition-colors whitespace-nowrap">
                        Semua
                    </a>
                    @foreach($libraryCategories as $libraryCategory)
                        <a href="{{ route('user.library.index', ['type' => $libraryCategory->name, 'search' => $search]) }}"
                            class="{{ $type == $libraryCategory->name ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' : 'border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium' }} py-2 sm:py-3 px-1 text-xs sm:text-sm transition-colors whitespace-nowrap">
                            {{ $libraryCategory->name }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <div id="library-grid" class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @php
                $gradients = [
                    'from-indigo-500 to-purple-600',
                    'from-emerald-500 to-teal-600',
                    'from-amber-500 to-orange-600',
                    'from-pink-500 to-rose-600',
                    'from-blue-500 to-cyan-600',
                    'from-violet-500 to-purple-600',
                    'from-red-500 to-orange-600',
                    'from-fuchsia-500 to-pink-600',
                ];
                $badgeStyles = [
                    ['text' => 'text-indigo-600', 'bg' => 'bg-indigo-50'],
                    ['text' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                    ['text' => 'text-amber-600', 'bg' => 'bg-amber-50'],
                    ['text' => 'text-pink-600', 'bg' => 'bg-pink-50'],
                    ['text' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                    ['text' => 'text-violet-600', 'bg' => 'bg-violet-50'],
                    ['text' => 'text-red-600', 'bg' => 'bg-red-50'],
                    ['text' => 'text-fuchsia-600', 'bg' => 'bg-fuchsia-50'],
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

                <div
                    class="library-item bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_-8px_rgba(99,102,241,0.3)] hover:border-indigo-500">

                    @if($library->cover_image)
                        <div class="h-28 sm:h-48 overflow-hidden">
                            <img src="{{ Storage::url($library->cover_image) }}" alt="{{ $library->title }}"
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="bg-gradient-to-br {{ $gradient }} h-28 sm:h-48 flex items-center justify-center">
                            @if($isVideo)
                                <svg class="w-12 h-12 sm:w-20 sm:h-20 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($isPeraturan)
                                <svg class="w-12 h-12 sm:w-20 sm:h-20 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            @else
                                <svg class="w-12 h-12 sm:w-20 sm:h-20 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            @endif
                        </div>
                    @endif

                    <div class="p-3 sm:p-4">
                        <span
                            class="text-[10px] sm:text-xs font-semibold {{ $badge['text'] }} {{ $badge['bg'] }} px-1.5 sm:px-2 py-0.5 sm:py-1 rounded">{{ $library->libraryCategory->name ?? 'Materi' }}</span>
                        <h3 class="font-bold text-gray-900 mt-1 sm:mt-2 mb-1 text-xs sm:text-base truncate">
                            {{ $library->title }}</h3>
                        @if($library->description)
                            <p class="text-[10px] sm:text-sm text-gray-600 mb-2 sm:mb-3 hidden sm:block truncate">
                                {{ $library->description }}</p>
                        @endif
                        <button x-data @click="$dispatch('open-modal', 'library-modal-{{ $library->id }}')"
                            class="w-full px-3 sm:px-4 py-1.5 sm:py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-xs sm:text-sm font-medium transition-colors cursor-pointer">
                            {{ $buttonLabel }}
                        </button>
                    </div>
                </div>

                <x-modal name="library-modal-{{ $library->id }}" title="{{ $library->title }}" maxWidth="sm:max-w-4xl">
                    <div class="flex flex-col lg:flex-row gap-4">

                        <div class="flex-1 min-h-[300px] sm:min-h-[500px]">
                            @if($library->file_path)
                                <iframe src="{{ Storage::url($library->file_path) }}"
                                    class="w-full h-[300px] sm:h-[500px] rounded-lg border" frameborder="0"></iframe>
                            @elseif($library->video_path)
                                <video src="{{ Storage::url($library->video_path) }}" class="w-full h-[300px] sm:h-[500px] rounded-lg bg-black" controls></video>
                            @elseif($library->external_link && (str_contains($library->external_link, 'youtube.com') || str_contains($library->external_link, 'youtu.be')))
                                @php
                                    $videoUrl = $library->external_link;
                                    // Convert YouTube watch URL to embed URL
                                    if (str_contains($videoUrl, 'youtube.com/watch')) {
                                        $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                                        $videoUrl = preg_replace('/&.*/', '', $videoUrl);
                                    } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                        $videoUrl = str_replace('youtu.be/', 'youtube.com/embed/', $videoUrl);
                                    }
                                @endphp
                                <iframe src="{{ $videoUrl }}" class="w-full h-[300px] sm:h-[500px] rounded-lg" frameborder="0"
                                    allowfullscreen></iframe>
                            @elseif($library->external_link)
                                <iframe src="{{ $library->external_link }}" class="w-full h-[300px] sm:h-[500px] rounded-lg border" frameborder="0"
                                    allowfullscreen tabindex="0" title="Link Preview"></iframe>
                            @else
                                <div class="flex items-center justify-center h-full bg-gray-50 rounded-lg border text-gray-400">
                                    <div class="text-center p-6">
                                        <svg class="mx-auto h-12 w-12 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <p class="text-sm">Tidak ada pratinjau yang tersedia.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="lg:w-64 shrink-0 space-y-3">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">Informasi</h4>
                                <dl class="space-y-2 text-sm">
                                    <div>
                                        <dt class="text-gray-500 text-xs">Kategori</dt>
                                        <dd class="font-medium text-gray-900">{{ $library->libraryCategory->name ?? '-' }}</dd>
                                    </div>
                                    @if($library->description)
                                        <div>
                                            <dt class="text-gray-500 text-xs">Deskripsi</dt>
                                            <dd class="text-gray-700">{{ $library->description }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>

                            @if($library->external_link)
                                <a href="{{ $library->external_link }}" target="_blank"
                                    class="flex items-center gap-2 w-full px-4 py-2.5 border border-indigo-200 text-indigo-600 rounded-lg hover:bg-indigo-50 text-sm font-medium transition-colors justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Buka Link Eksternal
                                </a>
                            @endif

                            @if($library->file_path)
                                <a href="{{ Storage::url($library->file_path) }}" target="_blank" download
                                    class="flex items-center gap-2 w-full px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition-colors justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Unduh File
                                </a>
                            @endif

                            @if($library->video_path)
                                <a href="{{ Storage::url($library->video_path) }}" target="_blank" download
                                    class="flex items-center gap-2 w-full px-4 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium transition-colors justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Unduh Video
                                </a>
                            @endif
                        </div>
                    </div>
                </x-modal>
            @empty
                <div class="col-span-2 md:col-span-2 lg:col-span-4 text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada materi</h3>
                    <p class="mt-1 text-gray-500">Materi perpustakaan belum ditambahkan atau tidak ditemukan.</p>
                    @if($search || $type)
                        <a href="{{ route('user.library.index') }}"
                            class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 text-sm font-medium">Reset Filter</a>
                    @endif
                </div>
            @endforelse
        </div>

        @if($libraries->hasPages())
            <div class="mt-6">
                {{ $libraries->appends(['type' => $type, 'search' => $search])->links() }}
            </div>
        @endif
    </div>
</x-dashboard::layouts.dashboard>