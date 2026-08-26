<x-dashboard::layouts.dashboard title="{{ data_get($content, 'name', 'Detail Materi') }} | SIRENATA">
    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto min-h-screen">
        {{-- Breadcrumb / Back Button --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('user.course.my-course.detail', $slug) }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left"></i> Kembali ke {{ data_get($course, 'course_name', 'Kursus') }}
            </a>
            
          
        </div>

        {{-- Main Content Container --}}
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            
            {{-- 1. Video Section (Menyatu di bagian atas) --}}
            @php
                $videoRaw = data_get($content, 'video') ?? data_get($content, 'video_url');
                $embedUrl = null;

                if ($videoRaw) {
                    // Regex Super Akurat untuk semua format YouTube (watch, youtu.be, embed, live, shorts)
                    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|live\/|shorts\/))([\w-]{11})/', $videoRaw, $matches)) {
                        // $matches[1] akan mengambil 11 karakter ID video YouTube (misal: 8r8nbFw_GZ8)
                        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?rel=0';
                    } else {
                        // Fallback jika ternyata link web biasa (bukan youtube)
                        $embedUrl = $videoRaw; 
                    }
                }
            @endphp

            @if ($embedUrl)
                <div class="w-full bg-slate-900 aspect-video relative border-b border-slate-200">
                    <iframe class="absolute top-0 left-0 w-full h-full" src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            @endif

            {{-- Body Content --}}
            <div class="p-6 sm:p-8 lg:p-10">
                
                {{-- Title --}}
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-6 leading-tight">
                    {{ data_get($content, 'name') }}
                </h1>

                {{-- 2. Text Content (Mengalir tanpa border tambahan) --}}
                @php
                    $contentText = is_object($content) ? $content->content_text : (is_array($content) ? ($content['content_text'] ?? null) : null);
                @endphp
                
                @if (!empty($contentText))
                    <div class="prose prose-sm sm:prose prose-slate max-w-none mb-8 leading-relaxed text-slate-700">
                        {!! $contentText !!}
                    </div>
                @endif

                {{-- 3. Document Attachment (Menempel elegan di bawah teks) --}}
                @php
                    $docUrl = data_get($content, 'document_url') ?? data_get($content, 'document');
                @endphp

                @if ($docUrl)
                    <div class="{{ !empty($contentText) ? 'mt-8 pt-8 border-t border-slate-100' : '' }}">
                        <a href="{{ $docUrl }}" target="_blank" class="group flex items-center p-4 rounded-lg border border-slate-200 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 transition-all duration-200">
                            <div class="w-12 h-12 rounded-lg bg-white border border-slate-200 flex items-center justify-center shrink-0 group-hover:border-blue-200 transition-colors">
                                <i class="fas fa-file-pdf text-xl text-red-500"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-bold text-slate-800 group-hover:text-blue-700 transition-colors">Materi Pendukung (Dokumen/PDF)</p>
                                <p class="text-xs text-slate-500 mt-0.5">Klik untuk mengunduh atau membaca lampiran materi ini.</p>
                            </div>
                            <div class="pl-4 shrink-0 text-slate-400 group-hover:text-blue-600 transition-colors">
                                <i class="fas fa-download text-lg"></i>
                            </div>
                        </a>
                    </div>
                @endif

                {{-- Empty State (jika ketiganya kosong) --}}
                @if (!$embedUrl && empty($contentText) && !$docUrl)
                    <div class="text-center py-12">
                        <i class="fas fa-folder-open text-5xl text-slate-200 mb-4"></i>
                        <p class="text-base font-bold text-slate-700">Materi Sedang Disiapkan</p>
                        <p class="text-sm text-slate-500 mt-1">Belum ada konten media atau tulisan yang diunggah untuk modul ini.</p>
                    </div>
                @endif
            </div>

            {{-- Action Footer --}}
            <div class="px-6 py-5 sm:px-8 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('user.course.my-course.detail', $slug) }}" class="w-full sm:w-auto text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors text-center">
                    <i class="fas fa-list-ul mr-1.5"></i> Lihat Modul Lainnya
                </a>
                
                @if(!data_get($content, 'is_completed'))
                    <form action="{{ route('user.course.content.complete', ['content' => data_get($content, 'id')]) }}" method="POST" class="w-full sm:w-auto m-0">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i> Selesai & Lanjutkan
                        </button>
                    </form>
                @else
                    <span class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-blue-700 bg-blue-100 border border-blue-200 rounded-lg text-center flex items-center justify-center gap-2">
                        <i class="fas fa-check-double"></i> Selesai Dipelajari
                    </span>
                @endif
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>