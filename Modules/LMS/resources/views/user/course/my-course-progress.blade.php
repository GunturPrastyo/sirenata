<x-dashboard::layouts.dashboard title="Kursus Saya - Belum Selesai | SIRENATA">
    <div class="p-4 sm:p-6 lg:p-8 bg-slate-50/50 min-h-screen">
        
        {{-- Header (Pengganti Breadcrumb) --}}
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kursus Saya</h1>
            <p class="text-sm text-slate-500 mt-1.5">Lanjutkan pembelajaran Anda dan tingkatkan kompetensi melalui modul yang tersedia.</p>
        </div>

        <div>
            {{-- Navigasi Tab --}}
            <div class="mb-6 sm:mb-8">
                <div class="border-b border-slate-200">
                    <nav class="flex space-x-6 overflow-x-auto pb-[-1px]">
                        <a
                            href="{{ route('user.course.my-course') }}"
                            class="border-b-2 py-3 px-1 text-sm whitespace-nowrap transition-colors border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium"
                        >
                            <span>Semua</span>
                        </a>

                        <a
                            href="{{ route('user.course.my-course.progress') }}"
                            class="border-b-2 py-3 px-1 text-sm whitespace-nowrap transition-colors border-indigo-600 text-indigo-600 font-bold"
                        >
                            <span>Belum Selesai ({{ $meta['total'] ?? 0 }})</span>
                        </a>

                        <a
                            href="{{ route('user.course.my-course.finish') }}"
                            class="border-b-2 py-3 px-1 text-sm whitespace-nowrap transition-colors border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium"
                        >
                            <span>Selesai</span>
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Grid Cards (Maksimal 3 per baris) --}}
            <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-6">
                @forelse ($courses as $course)
                    <a href="{{ route('user.course.my-course.detail', $course->slug) }}" class="group flex flex-col bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                        
                        {{-- Thumbnail & Badge --}}
                        <div class="relative h-48 overflow-hidden bg-slate-100">
                            @if (!empty($course->thumbnail_url))
                                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->course_name ?? $course->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-slate-300"></i>
                                </div>
                            @endif
                            
                            {{-- Badge Kategori --}}
                            <div class="absolute top-3 left-3">
                                <span class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-white bg-slate-900/70 backdrop-blur-md rounded-full shadow-sm">
                                    {{ $course->category->name ?? 'Umum' }}
                                </span>
                            </div>
                        </div>

                        {{-- Konten Text --}}
                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="text-base font-bold text-slate-800 leading-snug mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2" title="{{ $course->course_name ?? $course->name }}">
                                {{ $course->course_name ?? $course->name }}
                            </h3>
                            
                            {{-- Deskripsi Kursus --}}
                            <p class="text-xs text-slate-500 mb-5 line-clamp-2 leading-relaxed flex-1">
                                {{ $course->description ?? 'Deskripsi kursus belum tersedia. Silakan masuk untuk melihat detail materi pembelajaran.' }}
                            </p>

                            {{-- Progress Bar & Tombol Lanjutkan --}}
                            <div class="mt-auto pt-4 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Progress Belajar</span>
                                    <span class="text-xs font-extrabold text-indigo-600">
                                        {{ $course->progress ?? 0 }}%
                                    </span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mb-4">
                                    <div class="bg-indigo-600 h-full rounded-full transition-all duration-700" style="width: {{ $course->progress ?? 0 }}%"></div>
                                </div>

                                {{-- Tombol CTA --}}
                                <div class="w-full py-2.5 text-xs font-bold text-center rounded-xl transition-colors bg-indigo-50 text-indigo-700 group-hover:bg-indigo-600 group-hover:text-white">
                                    Lanjutkan Belajar
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    {{-- Empty State --}}
                    <div class="col-span-full flex flex-col items-center justify-center py-16 px-4 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                            <span class="text-4xl">📚</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Semua Kursus Selesai!</h3>
                        <p class="text-sm text-slate-500 max-w-sm">Anda tidak memiliki kursus yang sedang berjalan. Jelajahi lebih banyak kursus untuk menambah keahlian Anda.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if (! empty($courses) && count($courses) > 0)
                <div class="mt-8 flex justify-center gap-2">
                    <x-api-pagination :meta="$meta" />
                </div>
            @endif
        </div>
    </div>
</x-dashboard::layouts.dashboard>