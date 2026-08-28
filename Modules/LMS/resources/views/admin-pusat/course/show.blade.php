<x-dashboard::layouts.dashboard title="Detail Course: {{ $course->name }}">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-3">
               
                <li>
                    <div class="flex items-center">
                       
                        <a href="{{ route('admin-pusat.management-course.courses.index') }}" class="ml-1 text-sm font-medium text-slate-700 hover:text-indigo-600 md:ml-2">
                          <i class="fas fa-home mr-2"></i>  Daftar Course
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">{{ $course->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- KOLOM KIRI (Utama: Info & Kurikulum) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card Info Utama -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    @if (! empty($course->thumbnail_url))
                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->name }}" class="w-full h-72 object-cover" />
                    @else
                        <div class="w-full h-64 bg-slate-200 flex items-center justify-center">
                            <i class="fas fa-image text-slate-400 text-4xl"></i>
                        </div>
                    @endif

                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-full">
                                {{ $course->category->name ?? 'Tanpa Kategori' }}
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-slate-800 mb-4">{{ $course->name }}</h1>

                        <div class="prose prose-sm sm:prose max-w-none text-slate-600">
                            
                            <p>{{ $course->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card Kurikulum / Sections -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Header Kurikulum -->
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-slate-800">Kurikulum / Modul Belajar</h2>
                        <button type="button" x-data @click="$dispatch('open-modal', 'course_sections-{{ $course->slug }}')" class="px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-colors shadow-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Bagian
                        </button>
                    </div>

                    <!-- Modal Tambah Bagian (Section) -->
                    <x-modal name="course_sections-{{ $course->slug }}" title="Tambah Bagian Baru" maxWidth="sm:max-w-xl">
                        <x-validation-errors class="mb-4" />
                        <form action="{{ route('admin-pusat.management-course.course-sections.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="course_slug" value="{{ $course->slug }}" />
                            <div class="mb-5">
                                <h3 class="text-lg font-semibold text-slate-800">Tambah Bagian Baru</h3>
                                <p class="text-sm text-slate-500">Buat struktur bagian (section) untuk materi course Anda.</p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        Nama Bagian <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" required placeholder="Contoh: Bab 1: Pengenalan Dasar" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        Deskripsi <span class="text-slate-400 font-normal">(Opsional)</span>
                                    </label>
                                    <textarea name="description" rows="3" placeholder="Tuliskan gambaran singkat mengenai bagian ini..." class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-slate-100">
                                <button type="button" x-data @click="$dispatch('close-modal', 'course_sections-{{ $course->slug }}')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 transition-colors">Simpan Bagian</button>
                            </div>
                        </form>
                    </x-modal>

                    <div class="p-6">
                        @if (count($course->course_sections) > 0)
                            <div class="space-y-6">
                                <!-- Looping Bagian Materi -->
                                @foreach ($course->course_sections as $section)
                                    <div class="border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                                        <!-- Header Section -->
                                        <div class="bg-slate-50 px-4 py-3 border-b border-slate-200 flex justify-between items-center">
                                            <h3 class="font-medium text-slate-800">
                                                Bagian {{ $section->position }}: {{ $section->name }}
                                            </h3>
                                            <span class="text-xs font-medium text-slate-500 bg-slate-200 px-2 py-1 rounded-md">
                                                {{ count($section->contents) }} Materi
                                            </span>
                                        </div>

                                        <!-- List Content -->
                                        <ul class="divide-y divide-slate-100">
                                            @forelse ($section->contents as $content)
                                                <li class="px-4 py-3 hover:bg-slate-50 flex items-start gap-3 transition-colors group">
                                                    <div class=" text-indigo-500">
                                                        @if ($content->video_url)
                                                            <i class="fas fa-play-circle"></i>
                                                        @else
                                                            <i class="fas fa-file-alt"></i>
                                                        @endif
                                                    </div>

                                                    <div class="flex-1">
                                                        <p class="text-sm text-slate-700 font-medium">
                                                            {{ $content->name }}
                                                        </p>
                                                    </div>

                                                    <div class="flex items-center gap-2">
                                                        <!-- Lihat (Akses Halaman Show) -->
                                                        <a href="{{ route('admin-pusat.management-course.course-sections-contents.show', $content->id) }}?course_slug={{ $course->slug }}"
                                                           class="text-slate-400 hover:text-blue-600 transition-colors p-1" title="Lihat Materi">
                                                            <i class="fas fa-eye text-sm"></i>
                                                        </a>

                                                        <!-- Edit (Akses Halaman Edit) -->
                                                        <a href="{{ route('admin-pusat.management-course.course-sections-contents.edit', $content->id) }}?course_slug={{ $course->slug }}"
                                                           class="text-slate-400 hover:text-amber-600 transition-colors p-1" title="Edit Materi">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>

                                                        <!-- Delete (Tetap pakai Modal) -->
                                                        <button type="button" x-data @click="$dispatch('open-modal', 'delete-content-{{ $content->id }}')" class="text-slate-400 hover:text-red-600 transition-colors p-1" title="Hapus Materi">
                                                            <i class="fas fa-trash-alt text-sm"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Modal Hapus Content Materi -->
                                                    <x-modal name="delete-content-{{ $content->id }}" title="Konfirmasi Hapus" maxWidth="sm:max-w-xl">
                                                        <form action="{{ route('admin-pusat.management-course.course-sections-contents.destroy', $content->id) }}" method="POST" class="p-6">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="course_slug" value="{{ $course->slug }}" />
                                                            <div class="text-center">
                                                                <h3 class="text-lg font-semibold text-slate-800">Hapus Materi</h3>
                                                                <p class="text-sm text-slate-500 mt-2">Yakin hapus materi <strong>"{{ $content->name }}"</strong>?</p>
                                                            </div>
                                                            <div class="flex justify-center gap-3 mt-6">
                                                                <button type="button" x-data @click="$dispatch('close-modal', 'delete-content-{{ $content->id }}')" class="px-4 py-2 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg">Batal</button>
                                                                <button type="submit" class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg">Ya, Hapus</button>
                                                            </div>
                                                        </form>
                                                    </x-modal>
                                                </li>
                                            @empty
                                                <li class="px-4 py-4 text-sm text-slate-500 text-center bg-slate-50/50">Belum ada materi di bagian ini.</li>
                                            @endforelse
                                        </ul>

                                        <!-- Footer: Tombol Tambah Materi & Post Test Section (Dinamis: Tambah / Edit) -->
                                        @php
                                            $existingPostTest = \Modules\LMS\Models\PostTest::where('course_section_id', $section->id)->first();
                                        @endphp
                                        <div class="bg-white px-4 py-3 border-t border-slate-100 flex flex-wrap items-center justify-center gap-4">
                                            <!-- Tombol Tambah Materi -->
                                            <a href="{{ route('admin-pusat.management-course.course-sections-contents.create', ['course_slug' => $course->slug, 'section_id' => $section->id]) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center transition-colors">
                                                <i class="fas fa-plus-circle mr-1.5"></i> Tambah Materi Baru
                                            </a>
                                            
                                            <div class="w-px h-4 bg-slate-300 hidden sm:block"></div>
                                            
                                            <!-- Tombol Post Test Bagian (Dinamis) -->
                                            @if($existingPostTest)
                                                <a href="{{ route('admin-pusat.management-course.post-tests.edit', $existingPostTest->id) }}?course_slug={{ $course->slug }}" class="text-sm font-semibold text-amber-600 hover:text-amber-800 flex items-center transition-colors">
                                                    <i class="fas fa-edit mr-1.5"></i> Edit Post Test Bagian
                                                </a>
                                            @else
                                                <a href="{{ route('admin-pusat.management-course.post-tests.create', ['course_slug' => $course->slug, 'section_id' => $section->id]) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-800 flex items-center transition-colors">
                                                    <i class="fas fa-clipboard-check mr-1.5"></i> Tambah Post Test Bagian
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <!-- ============================================== -->
                                <!-- FINAL POST TEST (EVALUASI AKHIR MODUL) CARD    -->
                                <!-- ============================================== -->
                                @php
                                    $existingFinalTest = \Modules\LMS\Models\PostTest::where('course_id', $course->id)->whereNull('course_section_id')->first();
                                @endphp
                                <div class="border-2 border-emerald-100 bg-emerald-50/30 rounded-lg overflow-hidden shadow-sm mt-8 relative">
                                    <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                                    <div class="p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                                        <div class="flex items-start gap-4">
                                            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-full shrink-0 mt-1">
                                                <i class="fas fa-graduation-cap text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-base font-bold text-slate-800 mb-1">Evaluasi Akhir Course</h3>
                                                <p class="text-sm text-slate-600">
                                                    Post Test utama sebagai syarat penyelesaian kursus dan perhitungan nilai akhir.
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Tombol Buat / Edit Evaluasi Akhir (Dinamis) -->
                                        @if($existingFinalTest)
                                            <a href="{{ route('admin-pusat.management-course.post-tests.edit', $existingFinalTest->id) }}?course_slug={{ $course->slug }}" class="shrink-0 px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition-colors shadow-sm flex items-center gap-2">
                                                <i class="fas fa-edit"></i> Edit Evaluasi Akhir
                                            </a>
                                        @else
                                            <a href="{{ route('admin-pusat.management-course.post-tests.create', ['course_slug' => $course->slug, 'course_id' => $course->id]) }}" class="shrink-0 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-sm flex items-center gap-2">
                                                <i class="fas fa-plus"></i> Buat Evaluasi Akhir
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Empty State Kurikulum -->
                            <div class="text-center py-10">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-500 mb-4">
                                    <i class="fas fa-folder-open text-2xl"></i>
                                </div>
                                <h3 class="text-base font-medium text-slate-800 mb-1">Kurikulum Kosong</h3>
                                <p class="text-slate-500 text-sm mb-4">Mulai bangun struktur materi course Anda dengan menambahkan bagian pertama.</p>
                                <button type="button" x-data @click="$dispatch('open-modal', 'course_sections-{{ $course->slug }}')" class="px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition-colors">
                                    <i class="fas fa-plus mr-1"></i> Buat Bagian Pertama
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN (Sidebar: Aksi, Informasi, Catatan) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Card Aksi -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Aksi Course</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin-pusat.management-course.courses.edit', $course->slug) }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-amber-500 border border-transparent rounded-lg hover:bg-amber-600 transition-colors shadow-sm">
                            <i class="fas fa-edit mr-2"></i> Edit Course
                        </a>
                        <a href="{{ route('admin-pusat.management-course.courses.index') }}" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>

                <!-- Card Informasi Tambahan -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Informasi Tambahan</h3>
                    </div>
                    <div class="p-0">
                        <ul class="divide-y divide-slate-100">
                            <li class="flex justify-between items-center px-6 py-3.5">
                                <span class="text-sm text-slate-500">Total Siswa</span>
                                <span class="text-sm font-semibold text-slate-800">{{ $course->students_count ?? 0 }} Orang</span>
                            </li>
                            <li class="flex justify-between items-center px-6 py-3.5">
                                <span class="text-sm text-slate-500">Kategori</span>
                                <span class="text-sm font-semibold text-slate-800">{{ $course->category->name ?? '-' }}</span>
                            </li>
                            <!-- Dibuat Pada -->
                            <li class="flex justify-between items-center px-6 py-3.5">
                                <span class="text-sm text-slate-500">Dibuat Pada</span>
                                <span class="text-sm font-semibold text-slate-800">
                                    {{ \Carbon\Carbon::parse($course->created_at)->translatedFormat('d M Y') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Card Pengaturan Sertifikat (WIDGET BARU) -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg shrink-0">
                            <i class="fas fa-certificate text-lg"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">Sertifikat Kelulusan</h3>
                    </div>
                    <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                        Pastikan Anda telah mengatur template dan tanda tangan digital untuk sertifikat yang akan diterbitkan ke peserta kursus.
                    </p>
                    <a href="{{ route('admin-pusat.certificates.index') }}" class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors shadow-sm">
                        <i class="fas fa-cog mr-2"></i> Atur Sertifikat
                    </a>
                </div>

                <!-- Card Peringatan / Wajib Post Test -->
                <div class="bg-amber-50 rounded-xl border border-amber-200 p-5 flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-amber-800 mb-1">Catatan Penting</h4>
                        <p class="text-xs text-amber-700 leading-relaxed">
                            Anda <strong>wajib menambahkan Post Test</strong> pada setiap akhir bagian (section) materi sebagai syarat kelulusan peserta untuk lanjut ke bagian berikutnya.
                        </p>
                    </div>
                </div>

                <!-- Card Catatan / Pengaturan Lanjutan -->
                <div class="bg-indigo-50 rounded-xl border border-indigo-100 p-5 flex items-start gap-3">
                    <i class="fas fa-info-circle text-indigo-500 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-indigo-800 mb-1">Pengaturan Lanjutan</h4>
                        <p class="text-xs text-indigo-600 leading-relaxed">
                            Kelola Mentor, Benefits, dan Testimoni secara terpisah melalui panel navigasi utama.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>