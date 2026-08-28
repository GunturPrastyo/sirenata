<x-dashboard::layouts.dashboard title="Dashboard Pembelajaran">
    @push('styles')
        <link
            href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
            rel="stylesheet"
        />
    @endpush

    <div class="p-4 sm:p-6 lg:p-8 max-w-full mx-auto">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Card 1: Total Kursus -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">
                        Total Kursus
                    </p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                        {{ $stats['total'] }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>

            <!-- Card 2: Rata-rata Progress -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">
                        Rata-rata Progress
                    </p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                        {{ $stats['avg_progress'] }}%
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>

            <!-- Card 3: Kursus Selesai -->
            <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200 flex items-center justify-between transition-all duration-200 hover:shadow-md">
                <div>
                    <p class="text-slate-500 text-xs sm:text-sm font-semibold uppercase tracking-wider mb-1">
                        Kursus Selesai
                    </p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                        {{ $stats['selesai'] }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            <!-- Left Column: Lanjutkan Belajar -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <i class="fas fa-play-circle text-base"></i>
                        </div>
                        <h2 class="text-base font-bold text-slate-800">
                            Lanjutkan Belajar
                        </h2>
                    </div>

                    @if ($lastCourse)
                        <div class="bg-slate-900 rounded-lg p-5 sm:p-6 text-white relative overflow-hidden border border-slate-800 shadow-sm">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4 gap-4">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-blue-400 uppercase tracking-wider mb-1">
                                            Terakhir diakses
                                        </p>
                                        <h3 class="text-base sm:text-lg font-bold truncate text-white">
                                            {{ $lastCourse->name }}
                                        </h3>
                                    </div>
                                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-lg px-3 py-1.5 text-right shrink-0">
                                        <p class="text-lg font-extrabold text-blue-300">
                                            {{ $lastCourse->progress }}%
                                        </p>
                                        <p class="text-[10px] text-slate-300 uppercase tracking-wider font-medium">Selesai</p>
                                    </div>
                                </div>
                                
                                <div class="w-full bg-slate-800 rounded-full h-2 mb-5 overflow-hidden">
                                    <div
                                        class="bg-blue-500 rounded-full h-full transition-all duration-500"
                                        style="width: {{ $lastCourse->progress }}%"
                                    ></div>
                                </div>
                                
                                <a
                                    href="{{ route('user.course.my-course.detail', $lastCourse->slug) }}"
                                    class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-bold transition-all text-xs sm:text-sm w-full sm:w-auto shadow-sm"
                                >
                                    <span>Lanjutkan Belajar</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 rounded-lg p-8 text-center border border-dashed border-slate-200">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm text-slate-400">
                                <i class="fas fa-book-open text-lg"></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">Belum ada kursus yang sedang dipelajari</p>
                            <a
                                href="{{ route('user.course.my-course') }}"
                                class="inline-flex items-center gap-1.5 mt-3 text-blue-600 text-xs font-bold hover:underline"
                            >
                                <span>Lihat semua kursus</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Kursus Saya (Recent Courses) -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg p-5 sm:p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <i class="fas fa-graduation-cap text-base"></i>
                            </div>
                            <h2 class="text-base font-bold text-slate-800">Kursus Saya</h2>
                        </div>
                        <a
                            href="{{ route('user.course.my-course') }}"
                            class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1"
                        >
                            <span>Lihat semua</span>
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($recentCourses as $course)
                            <a
                                href="{{ route('user.course.my-course.detail', $course->slug) }}"
                                class="block bg-white border border-slate-200 rounded-lg p-3.5 transition-all duration-200 hover:border-blue-400 hover:shadow-sm group"
                            >
                                <div class="flex items-center gap-3.5">
                                    <img
                                        src="{{ $course->thumbnail_url ?? 'https://picsum.photos/seed/' . $course->id . '/120/80' }}"
                                        alt="{{ $course->name }}"
                                        class="w-16 h-14 rounded-lg object-cover flex-shrink-0 border border-slate-100"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2 mb-1.5">
                                            <h3 class="font-bold text-slate-800 text-sm truncate group-hover:text-blue-600 transition-colors">
                                                {{ $course->name }}
                                            </h3>
                                            
                                            @if ($course->pivot->status === 'completed')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-blue-700 bg-blue-50 border border-blue-200 shrink-0">
                                                    Selesai
                                                </span>
                                            @elseif ($course->pivot->status === 'in_progress')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-50 border border-amber-200 shrink-0">
                                                    Berjalan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-slate-600 bg-slate-100 border border-slate-200 shrink-0">
                                                    Terdaftar
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center justify-between text-xs text-slate-500 mb-1.5 font-medium">
                                            <span>Progress Belajar</span>
                                            <span class="font-bold text-slate-700">{{ $course->pivot->progress }}%</span>
                                        </div>
                                        
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div
                                                class="{{ $course->pivot->status === 'completed' ? 'bg-blue-600' : 'bg-amber-500' }} h-full rounded-full transition-all duration-300"
                                                style="width: {{ $course->pivot->progress }}%"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-10 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mx-auto mb-2 text-slate-400 shadow-sm">
                                    <i class="fas fa-folder-open text-sm"></i>
                                </div>
                                <p class="text-xs font-semibold text-slate-600">Belum ada kursus yang diikuti.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (! $profile || empty($profile->instansi))
        <!-- Modal Card Instansi -->
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
            style="background-color: rgba(15, 23, 42, 0.6)"
        >
            <div
                class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-slate-200"
            >
                <div class="p-6 sm:p-8">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <div
                            class="bg-blue-50 text-blue-600 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3 border border-blue-100 shadow-sm"
                        >
                            <svg
                                class="w-7 h-7"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                />
                            </svg>
                        </div>
                        <h2 class="text-xl font-extrabold text-slate-900">Pilih Instansi Anda</h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Lengkapi informasi institusi untuk melanjutkan akses pembelajaran
                        </p>
                    </div>

                    <!-- Form -->
                    <form
                        id="instansiForm"
                        method="POST"
                        action="{{ route('user.update-instansi') }}"
                        class="space-y-5"
                    >
                        @csrf

                        <!-- Asal Instansi -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                                Asal Instansi
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-3">
                                <label
                                    class="flex items-center justify-center px-3 py-2.5 border border-slate-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50/50 transition-all font-semibold text-sm text-slate-700"
                                >
                                    <input
                                        type="radio"
                                        name="asalInstansi"
                                        value="pusat"
                                        class="mr-2 text-blue-600 focus:ring-blue-500"
                                    />
                                    <span>Pusat</span>
                                </label>
                                <label
                                    class="flex items-center justify-center px-3 py-2.5 border border-slate-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50/50 transition-all font-semibold text-sm text-slate-700"
                                >
                                    <input
                                        type="radio"
                                        name="asalInstansi"
                                        value="provinsi"
                                        class="mr-2 text-blue-600 focus:ring-blue-500"
                                    />
                                    <span>Provinsi</span>
                                </label>
                                <label
                                    class="flex items-center justify-center px-3 py-2.5 border border-slate-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50/50 transition-all font-semibold text-sm text-slate-700"
                                >
                                    <input
                                        type="radio"
                                        name="asalInstansi"
                                        value="kabkota"
                                        class="mr-2 text-blue-600 focus:ring-blue-500"
                                    />
                                    <span>Kab/Kota</span>
                                </label>
                            </div>
                        </div>

                        <!-- Kementerian (Show if Pusat) -->
                        <div id="kementerianSection" class="hidden">
                            <label
                                for="kementerian"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2"
                            >
                                Kementerian/Lembaga
                                <span class="text-red-500">*</span>
                            </label>
                            <select id="kementerian" class="w-full">
                                <option value="">Pilih Kementerian/Lembaga</option>
                            </select>
                            <input type="hidden" name="instansi" id="finalInstansi" />
                        </div>

                        <!-- Provinsi (Show if Provinsi or Kab/Kota) -->
                        <div id="provinsiSection" class="hidden">
                            <label
                                for="provinsi"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2"
                            >
                                Provinsi
                                <span class="text-red-500">*</span>
                            </label>
                            <select id="provinsi" class="w-full" name="province_code">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinces as $prov)
                                    <option
                                        value="{{ $prov->code }}"
                                        data-name="{{ $prov->name }}"
                                    >
                                        {{ $prov->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kab/Kota (Show only if Kab/Kota selected) -->
                        <div id="kabkotaSection" class="hidden">
                            <label
                                for="kabkota"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2"
                            >
                                Kabupaten/Kota
                                <span class="text-red-500">*</span>
                            </label>
                            <select id="kabkota" class="w-full" name="regency_code">
                                <option value="">Pilih Kabupaten/Kota</option>
                            </select>
                        </div>

                        <!-- Instansi (Show for Provinsi and Kab/Kota) -->
                        <div id="instansiSection" class="hidden">
                            <label
                                for="instansi"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2"
                            >
                                Instansi
                                <span class="text-red-500">*</span>
                            </label>
                            <select id="instansi" class="w-full">
                                <option value="">Pilih Instansi</option>
                            </select>
                            <p class="mt-1.5 text-xs text-slate-500">
                                Pilih opsi "Lainnya" apabila instansi Anda tidak ditemukan dalam daftar.
                            </p>
                        </div>

                        <!-- Custom Instansi Input -->
                        <div id="customInstansiSection" class="hidden">
                            <label
                                for="customInstansi"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2"
                            >
                                Nama Instansi
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="instansi_lainnya"
                                id="customInstansi"
                                placeholder="Masukkan nama instansi"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                <p class="text-xs text-blue-800 font-bold mb-1">
                                    Petunjuk Penulisan:
                                </p>
                                <ul class="text-xs text-blue-700 space-y-0.5 ml-4 list-disc">
                                    <li>Gunakan huruf kapital pada awal setiap kata (Title Case).</li>
                                    <li>Contoh: <span class="font-semibold">"Dinas Pendidikan dan Kebudayaan"</span>.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Unit Kerja -->
                        <div id="unitKerjaSection" class="hidden">
                            <label
                                for="unitKerja"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2"
                            >
                                Unit Kerja
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="unit_kerja"
                                id="unitKerja"
                                placeholder="Contoh: Bagian SDM"
                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-3">
                            <button
                                type="submit"
                                class="w-full bg-blue-600 text-white py-3 px-5 rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors shadow-sm flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-save"></i>
                                <span>Simpan & Lanjutkan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <!-- jQuery (required for Select2) -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            // --- Instansi Form Logic ---
            @if (!$profile || empty($profile->instansi))
                $(document).ready(function() {
                    // Fetch Kementerian/Lembaga via API
                    $.ajax({
                        url: '{{ route("api.masterdata.institutions.index") }}?type=pusat',
                        type: 'GET',
                        success: function(response) {
                            if(response.success) {
                                response.data.forEach(k => {
                                    $('#kementerian').append(new Option(k.name, k.name));
                                });
                            }
                        }
                    });

                    // Initialize Select2
                    $('#kementerian, #provinsi, #kabkota, #instansi').select2({
                        placeholder: function() {
                            return $(this).find('option:first').text();
                        },
                        allowClear: true,
                        width: '100%'
                    });

                    // Handle Asal Instansi Selection
                    $('input[name="asalInstansi"]').on('change', function() {
                        const value = $(this).val();

                        // Reset all sections
                        $('#kementerianSection, #provinsiSection, #kabkotaSection, #instansiSection, #customInstansiSection, #unitKerjaSection')
                            .addClass('hidden');
                        $('#kementerian, #provinsi, #kabkota, #instansi, #customInstansi, #unitKerja').val('')
                            .trigger('change');

                        if (value === 'pusat') {
                            $('#kementerianSection, #unitKerjaSection').removeClass('hidden');
                        } else if (value === 'provinsi') {
                            $('#provinsiSection').removeClass('hidden');
                        } else if (value === 'kabkota') {
                            $('#provinsiSection').removeClass('hidden');
                        }
                    });

                    // Handle Provinsi Selection
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
                                    },
                                    error: function(xhr) {
                                        console.error('Failed to load regencies');
                                    }
                                });
                            } else if (asalInstansi === 'provinsi') {
                                // For provinsi, show instansi directly
                                populateInstansi();
                                $('#instansiSection').removeClass('hidden');
                                $('#kabkotaSection, #customInstansiSection').addClass('hidden');
                            }
                        }
                    });

                    // Function to populate Instansi dropdown
                    function populateInstansi() {
                        $('#instansi').empty().append(new Option('Pilih Instansi', ''));
                        $.ajax({
                            url: '{{ route("api.masterdata.institutions.index") }}?type=daerah',
                            type: 'GET',
                            success: function(response) {
                                if(response.success) {
                                    response.data.forEach(i => {
                                        $('#instansi').append(new Option(i.name, i.name));
                                    });
                                    // Add "Lainnya" option
                                    $('#instansi').append(new Option('Lainnya (Instansi tidak ada dalam daftar)', 'lainnya'));
                                }
                            }
                        });
                    }

                    // Handle Kab/Kota Selection
                    $('#kabkota').on('change', function() {
                        const kabkota = $(this).val();
                        if (kabkota) {
                            populateInstansi();
                            $('#instansiSection').removeClass('hidden');
                            $('#customInstansiSection, #unitKerjaSection').addClass('hidden');
                        }
                    });

                    // Handle Instansi Selection
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

                    // Handle Custom Instansi Input
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

                        // Force sync Select2 values ke elemen asli sebelum submit
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