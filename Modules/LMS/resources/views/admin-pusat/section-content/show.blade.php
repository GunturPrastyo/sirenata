<x-dashboard::layouts.dashboard title="Detail Materi: {{ $content->name }}">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-3 flex-wrap">
              
               
                <li>
                    <div class="flex items-center">
                       
                        <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}" class="ml-1 text-sm font-medium text-slate-700 hover:text-indigo-600 md:ml-2">
                          <i class="fas fa-home mr-2"></i>   {{ $course->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">{{ $content->section->name }}</span>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">{{ $content->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="max-w-full mx-auto bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Header Materi -->
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $content->name }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Bagian: <span class="font-medium text-slate-700">{{ $content->section->name }}</span></p>
                </div>
                <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}" class="text-xs font-medium text-slate-600 hover:text-slate-900 bg-white border border-slate-300 px-3 py-1.5 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <div class="p-6 md:p-8 space-y-8">
                <!-- Bagian Video Player -->
                @if ($content->video)
                    @php
                        $embedUrl = null;
                        $url = $content->video;
                        if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches) || preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                        } else {
                            $embedUrl = $url;
                        }
                    @endphp
                    
                    <div class="w-full">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-play-circle text-indigo-500"></i> Video Pembelajaran
                        </h3>
                        <div class="aspect-video w-full rounded-xl overflow-hidden shadow-sm border border-slate-200 bg-black">
                            <iframe class="w-full h-full" src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                @endif

                <!-- Bagian Konten Teks (Rich Text) -->
                @if (!empty($content->content_text))
                    <div class="w-full">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-file-alt text-indigo-500"></i> Materi Teks
                        </h3>
                        <!-- Menggunakan class 'prose' agar format HTML (heading, list, bold) ter-render rapi -->
                        <div class="prose prose-sm sm:prose max-w-none text-slate-700 bg-slate-50 p-6 rounded-xl border border-slate-100">
                            {!! $content->content_text !!}
                        </div>
                    </div>
                @endif

                <!-- Bagian Dokumen -->
                @if ($content->document_url)
                    <div class="w-full">
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-download text-indigo-500"></i> Lampiran Dokumen
                        </h3>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-3 bg-white rounded-lg border border-slate-100 shadow-sm shrink-0">
                                    <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700">Dokumen Pendukung</p>
                                    <p class="text-xs text-slate-400">Klik tombol di samping untuk mengunduh atau melihat dokumen.</p>
                                </div>
                            </div>
                            <a href="{{ $content->document_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shrink-0">
                                Buka Dokumen
                            </a>
                        </div>
                    </div>
                @endif

                <!-- State Kosong -->
                @if (!$content->video && empty($content->content_text) && !$content->document_url)
                    <div class="text-center py-12">
                        <i class="fas fa-box-open text-4xl text-slate-300 mb-3"></i>
                        <p class="text-sm font-medium text-slate-600">Konten Kosong</p>
                        <p class="text-xs text-slate-400">Materi ini belum memiliki video, teks, maupun dokumen lampiran.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>