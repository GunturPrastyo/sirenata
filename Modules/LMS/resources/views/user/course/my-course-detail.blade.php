<x-dashboard::layouts.dashboard title="My Course | SIRENATA">
    <div class="p-2 sm:p-6">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a
                        href="{{ url('/') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                            ></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <a href="{{ route('user.course.my-course') }}" class="ml-1 text-sm font-medium text-gray-700 md:ml-2">
                            Kursus Saya
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">
                                {{ $course->course_name }}
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="">
            @if ($course->status === 'completed')
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-lg p-4 sm:p-8 text-white mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex-1">
                        <span
                            class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-white/20 mb-2 sm:mb-3">
                            ✅ Selesai
                        </span>
                        <h1 class="text-xl sm:text-3xl font-bold mb-2 sm:mb-4">{{ $course->course_name }}</h1>
                        <div class="flex items-center gap-4 sm:gap-6 text-xs sm:text-sm">
                            <span>📚 {{ count($course->sections) }} Modul</span>
                            <span>🎓 Sertifikat Tersedia</span>
                        </div>
                    </div>
                    <div class="self-end sm:self-auto sm:ml-4">
                        <div
                            class="bg-white/20 backdrop-blur-sm rounded-lg p-3 sm:p-4 text-center min-w-[80px] sm:min-w-[120px]">
                            <div class="text-2xl sm:text-4xl font-bold">{{ $course->progress }}%</div>
                            <div class="text-xs sm:text-sm">Selesai</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if ($course->status === 'in_progress' || $course->status === 'enrolled')
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-4 sm:p-6 text-white mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 sm:gap-0">
                    <div class="flex-1">
                        <h1 class="text-lg sm:text-2xl font-bold mb-1 sm:mb-2">Indeks Pembangunan Ketenagakerjaan</h1>
                        <div class="flex items-center gap-4 sm:gap-6 text-xs sm:text-sm">
                            <span>📚 {{ count($course->sections) }} Modul</span>
                            <span>🎯 Progress: {{ $course->progress }}%</span>
                        </div>
                    </div>
                    <div class="self-end sm:self-auto sm:ml-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 sm:p-4 text-center min-w-[80px] sm:min-w-[100px]">
                            <div class="text-xl sm:text-3xl font-bold">{{ $course->progress }}%</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            <div class="space-y-3 sm:space-y-4" id="modules-container">
                <div class="bg-white rounded-lg shadow-sm  p-4 sm:p-6">
                    
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">
                        📚 Daftar Modul
                    </h2>

                    <p class="text-gray-600 mb-4 sm:mb-6 text-sm sm:text-base">
                        @if ($course->status === 'completed')
                            Semua modul telah diselesaikan.
                        @elseif ($course->status === 'in_progress' || $course->status === 'enrolled')
                            Progress Anda: {{ $course->progress }}%
                        @endif
                    </p>

                    <div 
                        x-data="{ 
                            activeAccordion: '', 
                            setActiveAccordion(id) { 
                                this.activeAccordion = (this.activeAccordion == id) ? '' : id 
                            } 
                        }" 
                        class="w-full mx-auto overflow-hidden text-sm bg-white border border-gray-200 divide-y divide-gray-200 rounded-md"
                    >
                        @forelse ($course->sections ?? [] as $index => $section)
                            <div x-data="{ id: $id('accordion') }" class="group">

                                <button 
                                    @click="setActiveAccordion(id)" 
                                    class="flex items-center justify-between w-full p-4 text-left hover:bg-gray-50"
                                >
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-semibold">
                                                Modul {{ $index + 1 }}
                                            </span>
                                            <span class="text-emerald-600 text-xs">
                                                @php
                                                    $total = count($section['section_contents'] ?? []);
                                                    $completed = $section['completed_count'] ?? 0;
                                                @endphp

                                                <span class="text-xs font-semibold 
                                                    {{ $completed === $total && $total > 0 
                                                        ? 'text-emerald-600' 
                                                        : 'text-yellow-600' }}">
                                                    
                                                    {{ $completed === $total && $total > 0 
                                                        ? '✓ Selesai' 
                                                        : 'Belum selesai' }}
                                                </span>
                                            </span>
                                        </div>

                                        <h3 class="font-semibold text-gray-900">
                                            {{ $section['name'] ?? 'Tanpa Judul' }}
                                        </h3>
                                    </div>

                                    {{-- ICON --}}
                                    <svg 
                                        class="w-4 h-4 transition-transform duration-200"
                                        :class="{ 'rotate-180': activeAccordion==id }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 9l6 6 6-6" />
                                    </svg>
                                </button>

                                {{-- CONTENT --}}
                                <div x-show="activeAccordion==id" x-collapse x-cloak>
                                    <div class="p-4 pt-0 text-gray-600 text-sm">
                                        {{-- <p>
                                            {{ $section['description'] ?? 'Tidak ada deskripsi.' }}
                                        </p> --}}


                                        @forelse ($section['section_contents'] as $content)
                                            <div class="flex items-center justify-between border border-gray-300 rounded p-3 mt-2">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800">
                                                        {{ $content['name'] }}
                                                    </p>
                                                </div>

                                                {{-- STATUS --}}
                                                <div class="flex items-center gap-x-3">
                                                    <span class="text-xs font-semibold
                                                    {{ $content['is_completed'] ? 'text-emerald-600' : 'text-gray-400' }}">
                                                    
                                                    {{ $content['is_completed'] ? '✓ Selesai' : 'Belum' }}
                                                    </span>
                                                    <a href="#"
                                                        class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium text-center text-sm">
                                                        Lihat Modul
                                                    </a>
                                                </div>

                                            </div>

                                            @empty
                                            <div class="p-4 text-gray-500 text-sm">
                                                Belum ada Konten Video/Materi.
                                            </div>
                                        @endforelse

                                    </div>
                                </div>

                            </div>
                        @empty
                            <div class="p-4 text-gray-500 text-sm">
                                Belum ada modul.
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>

            
        </div>
    </div>
</x-dashboard::layouts.dashboard>