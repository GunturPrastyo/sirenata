<x-dashboard::layouts.dashboard title="Dashboard Pembelajaran">
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <!-- Script Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto space-y-6">

        <!-- ===================================== -->
        <!-- 1. STATS GRID                         -->
        <!-- ===================================== -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <div
                class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">Total Kursus
                    </p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $stats['total'] }}</h3>
                </div>
                <!-- PERBAIKAN: Ikon Stats 1 (Warna Gelap, Teks Putih, Ukuran Tetap agar tidak gepeng) -->
                <div
                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#13416B] text-white flex items-center justify-center shrink-0 shadow-sm border border-[#0f3354]">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>

            <div
                class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">Rata-rata
                        Progress</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $stats['avg_progress'] }}%</h3>
                </div>
                <!-- PERBAIKAN: Ikon Stats 2 -->
                <div
                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#13416B] text-white flex items-center justify-center shrink-0 shadow-sm border border-[#0f3354]">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>

            <div
                class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:border-[#13416B]/30 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">Kursus
                        Selesai</p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-[#13416B]">{{ $stats['selesai'] }}</h3>
                </div>
                <!-- PERBAIKAN: Ikon Stats 3 -->
                <div
                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-[#13416B] text-white flex items-center justify-center shrink-0 shadow-sm border border-[#0f3354]">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- ===================================== -->
        <!-- 2. ANALITIK EVALUASI FULL WIDTH       -->
        <!-- ===================================== -->
        <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200">
            <div
                class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <!-- PERBAIKAN: Ikon Header Evaluasi -->
                    <div
                        class="w-10 h-10 sm:w-11 sm:h-11 flex items-center justify-center bg-[#13416B] text-white rounded-xl shrink-0 shadow-sm border border-[#0f3354]">
                        <i class="fas fa-chart-bar text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Evaluasi Anda per Kursus</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Perbandingan skor post-test Anda dengan
                            rata-rata peserta lain</p>
                    </div>
                </div>

                <!-- Dropdown Filter Kursus -->
                @if (isset($chartDataByCourse) && count($chartDataByCourse) > 0)
                    <div class="w-full sm:w-auto sm:max-w-xs shrink-0">
                        <select id="courseChartFilter"
                            class="w-full text-sm border-slate-200 rounded-lg focus:ring-[#13416B] focus:border-[#13416B] text-ellipsis overflow-hidden pr-8 cursor-pointer">
                            @foreach ($chartDataByCourse as $cId => $cData)
                                <option value="{{ $cId }}">{{ $cData['course_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            @if (isset($chartDataByCourse) && count($chartDataByCourse) > 0)
                <div class="relative w-full overflow-hidden" style="min-height: 280px;">
                    <canvas id="postTestChart"></canvas>
                </div>
            @else
                <div class="bg-slate-50 rounded-lg p-10 text-center border border-dashed border-slate-200 my-4">
                    <div
                        class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                        <i class="fas fa-chart-area text-xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Belum ada data evaluasi</p>
                    <p class="text-xs text-slate-500 mt-1">Daftar dan selesaikan modul kursus untuk memunculkan laporan
                        analitik evaluasi.</p>
                </div>
            @endif
        </div>

        <!-- ===================================== -->
        <!-- 3. GRID BAWAH (LANJUTKAN & RECENT)    -->
        <!-- ===================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

            <!-- KOLOM KIRI: Lanjutkan Belajar -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex flex-col h-full">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100 shrink-0">
                    <!-- PERBAIKAN: Ikon Header Lanjutkan Belajar -->
                    <div
                        class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-[#13416B] text-white rounded-xl shrink-0 shadow-sm border border-[#0f3354]">
                        <i class="fas fa-play-circle text-base"></i>
                    </div>
                    <h2 class="text-base font-bold text-slate-800">
                        Lanjutkan Belajar
                    </h2>
                </div>

                @if ($lastCourse)
                    <div
                        class="bg-white rounded-lg border border-slate-200 overflow-hidden shadow-sm flex flex-col flex-1 transition-shadow hover:shadow-md">

                        <!-- Header / Thumbnail (Tanpa Bintik Dekoratif) -->
                        <div
                            class="h-36 sm:h-40 bg-[#184A78] relative flex items-center justify-center overflow-hidden shrink-0">

                            @if (isset($lastCourse->category_name))
                                <span
                                    class="absolute top-4 left-4 px-3 py-1 text-[10px] font-bold rounded-full bg-white/20 border border-white/30 text-white uppercase tracking-wider backdrop-blur-sm z-10">
                                    {{ $lastCourse->category_name }}
                                </span>
                            @endif

                            @php
                                $words = explode(' ', $lastCourse->name);
                                $initials = '';
                                foreach (array_slice($words, 0, 2) as $w) {
                                    $initials .= strtoupper(substr($w, 0, 1));
                                }
                                if (strlen($initials) < 2) {
                                    $initials = substr(strtoupper($lastCourse->name), 0, 2);
                                }
                            @endphp
                            <h2 class="text-[64px] font-normal text-white leading-none tracking-tight relative z-10"
                                style="font-family: Arial, sans-serif;">
                                {{ $initials }}
                            </h2>
                        </div>

                        <!-- Body & Deskripsi -->
                        <div class="p-5 sm:p-6 flex flex-col flex-1">
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-2">Aktivitas Terakhir
                            </p>
                            <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2 leading-snug line-clamp-2">
                                {{ $lastCourse->name }}
                            </h3>

                            <p class="text-sm text-slate-500 line-clamp-3 mb-6 leading-relaxed">
                                {{ $lastCourse->description ?? 'Lanjutkan materi pembelajaran Anda pada kursus ini untuk meningkatkan kompetensi dan mendapatkan sertifikat kelulusan.' }}
                            </p>

                            <div class="mt-auto">
                                <div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-2">
                                    <span>Progress Belajar</span>
                                    <span class="text-[#13416B] text-sm">{{ $lastCourse->progress }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 mb-6 overflow-hidden">
                                    <div class="bg-[#13416B] rounded-full h-full transition-all duration-500"
                                        style="width: {{ $lastCourse->progress }}%"></div>
                                </div>

                                <a href="{{ route('user.course.my-course.detail', $lastCourse->slug) }}"
                                    class="flex items-center justify-center gap-2 w-full bg-[#13416B] hover:bg-[#0f3354] text-white px-5 py-3 rounded-lg font-bold transition-all text-sm shadow-sm">
                                    <i class="fas fa-play"></i>
                                    <span>Lanjutkan Materi</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div
                        class="bg-slate-50 rounded-lg p-12 text-center border border-dashed border-slate-200 mt-2 flex-1 flex flex-col justify-center items-center">
                        <div
                            class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm text-slate-400">
                            <i class="fas fa-book-open text-2xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Belum ada aktivitas belajar</p>
                        <a href="{{ route('user.course.my-course') }}"
                            class="inline-flex items-center justify-center gap-2 mt-4 px-6 py-2.5 bg-white border border-slate-200 rounded-lg text-[#13416B] text-sm font-bold hover:bg-slate-50 shadow-sm transition-all">
                            <span>Lihat Katalog Kursus</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                @endif
            </div>

            <!-- KOLOM KANAN: Kursus Saya (Recent Courses - Maks 4) -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <!-- PERBAIKAN: Ikon Header Kursus Saya -->
                        <div
                            class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center bg-[#13416B] text-white rounded-xl shrink-0 shadow-sm border border-[#0f3354]">
                            <i class="fas fa-graduation-cap text-base"></i>
                        </div>
                        <h2 class="text-base font-bold text-slate-800">Kursus Saya</h2>
                    </div>
                    <a href="{{ route('user.course.my-course') }}"
                        class="text-xs font-bold text-[#13416B] hover:underline flex items-center gap-1">
                        <span>Lihat semua</span>
                    </a>
                </div>

                <div class="space-y-3 flex-1 flex flex-col justify-between">
                    @forelse ($recentCourses as $course)
                        <a href="{{ route('user.course.my-course.detail', $course->slug) }}"
                            class="block bg-white border border-slate-200 rounded-lg p-3.5 transition-all duration-200 hover:border-[#13416B]/40 hover:shadow-sm group">
                            <div class="flex items-start gap-3.5">
                                <!-- Thumbnail List (Tanpa Bintik Dekoratif) -->
                                <div
                                    class="w-14 h-14 bg-[#184A78] rounded-lg flex items-center justify-center shrink-0 mt-1 shadow-inner relative overflow-hidden">
                                    @php
                                        $words = explode(' ', $course->name);
                                        $initials = '';
                                        foreach (array_slice($words, 0, 2) as $w) {
                                            $initials .= strtoupper(substr($w, 0, 1));
                                        }
                                        if (strlen($initials) < 2) {
                                            $initials = substr(strtoupper($course->name), 0, 2);
                                        }
                                    @endphp
                                    <span class="text-xl font-normal tracking-tight text-white relative z-10"
                                        style="font-family: Arial, sans-serif;">{{ $initials }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <h3
                                            class="font-bold text-slate-800 text-sm truncate group-hover:text-[#13416B] transition-colors">
                                            {{ $course->name }}</h3>
                                        @if ($course->pivot->status === 'completed')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-[#13416B] bg-[#13416B]/10 border border-[#13416B]/20 shrink-0">Selesai</span>
                                        @elseif ($course->pivot->status === 'in_progress')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-slate-700 bg-slate-100 border border-slate-200 shrink-0">Berjalan</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50 border border-slate-200 shrink-0">Terdaftar</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 line-clamp-2 mb-2 leading-relaxed">
                                        {{ $course->description ?? 'Deskripsi kursus tidak tersedia.' }}
                                    </p>
                                    <div
                                        class="flex items-center justify-between text-[11px] text-slate-500 mb-1.5 font-medium">
                                        <span>Progress Belajar</span>
                                        <span class="font-bold text-slate-700">{{ $course->pivot->progress }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-[#13416B] h-full rounded-full transition-all duration-300"
                                            style="width: {{ $course->pivot->progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div
                            class="text-center py-10 bg-slate-50 rounded-lg border border-dashed border-slate-200 flex-1 flex flex-col justify-center">
                            <div
                                class="w-10 h-10 bg-white rounded-full flex items-center justify-center mx-auto mb-2 text-slate-400 shadow-sm">
                                <i class="fas fa-folder-open text-sm"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-600">Belum ada kursus yang diikuti.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if (!$profile || empty($profile->instansi))
        <!-- Modal Card Instansi -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
            style="background-color: rgba(15, 23, 42, 0.6)">
            <div
                class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-slate-200">
                <div class="p-6 sm:p-8">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <div
                            class="bg-[#13416B]/10 text-[#13416B] w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3 border border-[#13416B]/20 shadow-sm">
                            <i class="fas fa-building text-2xl"></i>
                        </div>
                        <h2 class="text-xl font-extrabold text-slate-900">Pilih Instansi Anda</h2>
                        <p class="text-sm text-slate-500 mt-1">Lengkapi informasi institusi untuk melanjutkan akses
                            pembelajaran</p>
                    </div>

                    <!-- Form Instansi -->
                    <form id="instansiForm" method="POST" action="{{ route('user.update-instansi') }}"
                        class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Asal
                                Instansi <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-3">
                                <label
                                    class="flex items-center justify-center px-3 py-2.5 border border-slate-200 rounded-lg cursor-pointer hover:border-[#13416B] hover:bg-[#13416B]/5 transition-all font-semibold text-sm text-slate-700">
                                    <input type="radio" name="asalInstansi" value="pusat"
                                        class="mr-2 text-[#13416B] focus:ring-[#13416B]"> <span>Pusat</span>
                                </label>
                                <label
                                    class="flex items-center justify-center px-3 py-2.5 border border-slate-200 rounded-lg cursor-pointer hover:border-[#13416B] hover:bg-[#13416B]/5 transition-all font-semibold text-sm text-slate-700">
                                    <input type="radio" name="asalInstansi" value="provinsi"
                                        class="mr-2 text-[#13416B] focus:ring-[#13416B]"> <span>Provinsi</span>
                                </label>
                                <label
                                    class="flex items-center justify-center px-3 py-2.5 border border-slate-200 rounded-lg cursor-pointer hover:border-[#13416B] hover:bg-[#13416B]/5 transition-all font-semibold text-sm text-slate-700">
                                    <input type="radio" name="asalInstansi" value="kabkota"
                                        class="mr-2 text-[#13416B] focus:ring-[#13416B]"> <span>Kab/Kota</span>
                                </label>
                            </div>
                        </div>

                        <div id="kementerianSection" class="hidden">
                            <label for="kementerian"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kementerian/Lembaga
                                <span class="text-red-500">*</span></label>
                            <select id="kementerian" class="w-full">
                                <option value="">Pilih Kementerian/Lembaga</option>
                            </select>
                            <input type="hidden" name="instansi" id="finalInstansi" />
                        </div>

                        <div id="provinsiSection" class="hidden">
                            <label for="provinsi"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Provinsi
                                <span class="text-red-500">*</span></label>
                            <select id="provinsi" class="w-full" name="province_code">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinces as $prov)
                                    <option value="{{ $prov->code }}" data-name="{{ $prov->name }}">
                                        {{ $prov->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="kabkotaSection" class="hidden">
                            <label for="kabkota"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kabupaten/Kota
                                <span class="text-red-500">*</span></label>
                            <select id="kabkota" class="w-full" name="regency_code">
                                <option value="">Pilih Kabupaten/Kota</option>
                            </select>
                        </div>

                        <div id="instansiSection" class="hidden">
                            <label for="instansi"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Instansi
                                <span class="text-red-500">*</span></label>
                            <select id="instansi" class="w-full">
                                <option value="">Pilih Instansi</option>
                            </select>
                            <p class="mt-1.5 text-xs text-slate-500">Pilih opsi "Lainnya" apabila instansi Anda tidak
                                ditemukan.</p>
                        </div>

                        <div id="customInstansiSection" class="hidden">
                            <label for="customInstansi"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nama
                                Instansi <span class="text-red-500">*</span></label>
                            <input type="text" name="instansi_lainnya" id="customInstansi"
                                placeholder="Masukkan nama instansi"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#13416B] focus:border-[#13416B]" />
                        </div>

                        <div id="unitKerjaSection" class="hidden">
                            <label for="unitKerja"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Unit Kerja
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="unit_kerja" id="unitKerja" placeholder="Contoh: Bagian SDM"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#13416B] focus:border-[#13416B]" />
                        </div>

                        <div class="pt-3">
                            <button type="submit"
                                class="w-full bg-[#13416B] text-white py-3 px-5 rounded-lg text-sm font-bold hover:bg-[#0f3354] transition-colors shadow-sm flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i> <span>Simpan & Lanjutkan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
           // --- SCRIPT UNTUK GRAFIK BAR CHART (HORIZONTAL) POST-TEST ---
           // --- SCRIPT UNTUK GRAFIK BAR CHART (HORIZONTAL) POST-TEST ---
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('postTestChart');

                // FUNGSI: Memecah teks panjang menjadi beberapa baris (Array of Strings)
                function formatMultilineLabel(text) {
                    if (!text) return text;
                    const maxChars = window.innerWidth >= 640 ? 35 : 20; // Batas huruf per baris
                    const words = text.split(' ');
                    let lines = [];
                    let currentLine = '';

                    words.forEach(word => {
                        if ((currentLine + word).length > maxChars) {
                            if (currentLine.trim() !== '') lines.push(currentLine.trim());
                            currentLine = word + ' ';
                        } else {
                            currentLine += word + ' ';
                        }
                    });
                    if (currentLine.trim() !== '') lines.push(currentLine.trim());
                    return lines;
                }

                if (ctx && @json(isset($chartDataByCourse) ? count($chartDataByCourse) : 0) > 0) {
                    const allChartData = @json($chartDataByCourse ?? []);

                    const courseKeys = Object.keys(allChartData);
                    let currentCourseId = courseKeys[0];
                    let currentData = allChartData[currentCourseId];

                    const postTestChart = new Chart(ctx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: currentData.labels.map(label => formatMultilineLabel(label)),
                            datasets: [{
                                    label: 'Skor Anda',
                                    data: currentData.user_scores,
                                    backgroundColor: '#13416B',
                                    borderRadius: 4,
                                    barPercentage: 0.6,
                                    categoryPercentage: 0.8
                                },
                                {
                                    label: 'Rata-rata Peserta',
                                    data: currentData.avg_scores,
                                    backgroundColor: '#cbd5e1',
                                    borderRadius: 4,
                                    barPercentage: 0.6,
                                    categoryPercentage: 0.8
                                }
                            ]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        boxWidth: 8,
                                        boxPadding: 12, 
                                        padding: 20, 
                                        font: {
                                            size: 11,
                                            family: "'Inter', sans-serif"
                                        }
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    titleFont: { size: 13 },
                                    bodyFont: { size: 12 },
                                    padding: 10,
                                    cornerRadius: 8,
                                    callbacks: {
                                        title: function(context) {
                                            return context[0].label.replaceAll(',', ' ');
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    max: 100,
                                    grid: { color: '#f1f5f9' },
                                    ticks: {
                                        font: { size: 10 },
                                        stepSize: 20
                                    }
                                },
                                y: {
                                    grid: { display: false },
                                    ticks: {
                                        font: {
                                            size: 11,
                                            family: "'Inter', sans-serif",
                                            lineHeight: 1.3
                                        },
                                        autoSkip: false,
                                        padding: 8
                                    },
                                    afterFit: function(scaleInstance) {
                                        // PERBAIKAN 1: Lebar paksa dikurangi jadi 180 agar teks mempet dengan garis grafik
                                        if (window.innerWidth >= 640) {
                                            if (scaleInstance.width < 180) {
                                                scaleInstance.width = 180;
                                            }
                                        } else {
                                            if (scaleInstance.width < 120) {
                                                scaleInstance.width = 120;
                                            }
                                        }
                                    }
                                }
                            },
                            interaction: {
                                mode: 'index',
                                axis: 'y',
                                intersect: false
                            }
                        }
                    });

                    // PERBAIKAN 2: Mengurangi tinggi minimum canvas (dari 320 jadi 180) agar baris tidak berjauhan
                    const filterSelect = document.getElementById('courseChartFilter');
                    if (filterSelect) {
                        filterSelect.addEventListener('change', function() {
                            const selectedId = this.value;
                            const newData = allChartData[selectedId];

                            if (newData) {
                                const newHeight = Math.max(180, newData.labels.length * 80);
                                ctx.parentElement.style.height = newHeight + 'px';

                                postTestChart.data.labels = newData.labels.map(label => formatMultilineLabel(label));
                                postTestChart.data.datasets[0].data = newData.user_scores;
                                postTestChart.data.datasets[1].data = newData.avg_scores;
                                postTestChart.update();
                            }
                        });

                        const initialHeight = Math.max(180, currentData.labels.length * 80);
                        ctx.parentElement.style.height = initialHeight + 'px';
                    }
                }
            });
            // --- Instansi Form Logic ---
            @if (!$profile || empty($profile->instansi))
                $(document).ready(function() {
                    $.ajax({
                        url: '{{ route('api.masterdata.institutions.index') }}?type=pusat',
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                response.data.forEach(k => {
                                    $('#kementerian').append(new Option(k.name, k.name));
                                });
                            }
                        }
                    });

                    $('#kementerian, #provinsi, #kabkota, #instansi').select2({
                        placeholder: function() {
                            return $(this).find('option:first').text();
                        },
                        allowClear: true,
                        width: '100%'
                    });

                    $('input[name="asalInstansi"]').on('change', function() {
                        const value = $(this).val();
                        $('#kementerianSection, #provinsiSection, #kabkotaSection, #instansiSection, #customInstansiSection, #unitKerjaSection')
                            .addClass('hidden');
                        $('#kementerian, #provinsi, #kabkota, #instansi, #customInstansi, #unitKerja').val('')
                            .trigger('change');

                        if (value === 'pusat') {
                            $('#kementerianSection, #unitKerjaSection').removeClass('hidden');
                        } else if (value === 'provinsi' || value === 'kabkota') {
                            $('#provinsiSection').removeClass('hidden');
                        }
                    });

                    $('#provinsi').on('change', function() {
                        const provinsiCode = $(this).val();
                        const asalInstansi = $('input[name="asalInstansi"]:checked').val();

                        if (provinsiCode) {
                            if (asalInstansi === 'kabkota') {
                                $.ajax({
                                    url: '{{ route('user.get-regencies') }}',
                                    type: 'GET',
                                    data: {
                                        province_code: provinsiCode
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            $('#kabkota').empty().append(new Option(
                                                'Pilih Kabupaten/Kota', ''));
                                            response.data.forEach(regency => {
                                                $('#kabkota').append(new Option(regency
                                                    .name, regency.code));
                                            });
                                            $('#kabkotaSection').removeClass('hidden');
                                            $('#instansiSection, #customInstansiSection, #unitKerjaSection')
                                                .addClass('hidden');
                                        }
                                    }
                                });
                            } else if (asalInstansi === 'provinsi') {
                                populateInstansi();
                                $('#instansiSection').removeClass('hidden');
                                $('#kabkotaSection, #customInstansiSection').addClass('hidden');
                            }
                        }
                    });

                    function populateInstansi() {
                        $('#instansi').empty().append(new Option('Pilih Instansi', ''));
                        $.ajax({
                            url: '{{ route('api.masterdata.institutions.index') }}?type=daerah',
                            type: 'GET',
                            success: function(response) {
                                if (response.success) {
                                    response.data.forEach(i => {
                                        $('#instansi').append(new Option(i.name, i.name));
                                    });
                                    $('#instansi').append(new Option(
                                        'Lainnya (Instansi tidak ada dalam daftar)', 'lainnya'));
                                }
                            }
                        });
                    }

                    $('#kabkota').on('change', function() {
                        if ($(this).val()) {
                            populateInstansi();
                            $('#instansiSection').removeClass('hidden');
                            $('#customInstansiSection, #unitKerjaSection').addClass('hidden');
                        }
                    });

                    $('#instansi').on('change', function() {
                        const value = $(this).val();
                        if (value === 'lainnya') {
                            $('#customInstansiSection').removeClass('hidden');
                            $('#unitKerjaSection').addClass('hidden');
                        } else if (value) {
                            $('#customInstansiSection').addClass('hidden');
                            $('#unitKerjaSection').removeClass('hidden');
                        } else {
                            $('#customInstansiSection, #unitKerjaSection').addClass('hidden');
                        }
                    });

                    $('#customInstansi').on('input', function() {
                        if ($(this).val().trim()) {
                            $('#unitKerjaSection').removeClass('hidden');
                        } else {
                            $('#unitKerjaSection').addClass('hidden');
                        }
                    });

                    $('#instansiForm').on('submit', function(e) {
                        const asalInstansi = $('input[name="asalInstansi"]:checked').val();
                        if (!asalInstansi) {
                            e.preventDefault();
                            alert('Pilih asal instansi terlebih dahulu!');
                            return;
                        }

                        if (asalInstansi === 'pusat') {
                            const kemVal = $('#kementerian').val();
                            if (!kemVal) {
                                e.preventDefault();
                                alert('Pilih Kementerian/Lembaga!');
                                return;
                            }
                            $('#finalInstansi').val(kemVal);
                        }

                        if (asalInstansi === 'provinsi' || asalInstansi === 'kabkota') {
                            const instansiVal = $('#instansi').val();
                            if (instansiVal === 'lainnya') {
                                $('#finalInstansi').val($('#customInstansi').val().trim());
                            } else {
                                $('#finalInstansi').val(instansiVal);
                            }
                        }
                    });
                });
            @endif
        </script>
    @endpush
</x-dashboard::layouts.dashboard>
