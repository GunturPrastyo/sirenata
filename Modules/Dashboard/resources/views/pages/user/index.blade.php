<x-dashboard::layouts.dashboard title="Dashboard Pembelajaran">
    @push('styles')
        <link
            href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
            rel="stylesheet"
        />
    @endpush

    <div class="p-2 sm:p-6">
        <!-- Breadcrumb -->
        <x-breadcrumb :items="[['label' => 'Dashboard']]" />

        <!-- Stats Grid -->
        <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-4 sm:mb-6">
            <div
                class="bg-white rounded-lg p-3 sm:p-5 shadow-sm border-l-4 border-indigo-500 transition-all duration-300 hover:translate-x-1"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium mb-1">
                            Total Kursus
                        </p>
                        <h3 class="text-xl sm:text-3xl font-bold text-gray-900">
                            {{ $stats['total'] }}
                        </h3>
                    </div>
                    <div class="bg-indigo-100 p-2 sm:p-3 rounded-full">
                        <svg
                            class="w-5 h-5 sm:w-8 sm:h-8 text-indigo-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-lg p-3 sm:p-5 shadow-sm border-l-4 border-emerald-500 transition-all duration-300 hover:translate-x-1"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium mb-1">
                            Rata-rata Progress
                        </p>
                        <h3 class="text-xl sm:text-3xl font-bold text-gray-900">
                            {{ $stats['avg_progress'] }}
                        </h3>
                    </div>
                    <div class="bg-emerald-100 p-2 sm:p-3 rounded-full">
                        <svg
                            class="w-5 h-5 sm:w-8 sm:h-8 text-emerald-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-lg p-3 sm:p-5 shadow-sm border-l-4 border-amber-500 transition-all duration-300 hover:translate-x-1"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs sm:text-sm font-medium mb-1">
                            Kursus Selesai
                        </p>
                        <h3 class="text-xl sm:text-3xl font-bold text-gray-900">
                            {{ $stats['selesai'] }}
                        </h3>
                    </div>
                    <div class="bg-amber-100 p-2 sm:p-3 rounded-full">
                        <svg
                            class="w-5 h-5 sm:w-8 sm:h-8 text-amber-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Left Column -->
            <div class="space-y-4 sm:space-y-6">
                <div class="sm:bg-white rounded-lg p-3 sm:p-6 sm:shadow-sm">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">
                        Lanjutkan Belajar
                    </h2>

                    @if ($lastCourse)
                        <div
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-4 sm:p-6 text-white"
                        >
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <div class="flex-1 min-w-0 mr-2">
                                    <p class="text-xs sm:text-sm text-blue-100 mb-1">
                                        Terakhir diakses
                                    </p>
                                    <h3 class="text-sm sm:text-xl font-bold truncate">
                                        {{ $lastCourse->name }}
                                    </h3>
                                </div>
                                <div
                                    class="bg-white/20 backdrop-blur-sm rounded-lg px-2 sm:px-4 py-1 sm:py-2 flex-shrink-0"
                                >
                                    <p class="text-lg sm:text-2xl font-bold">
                                        {{ $lastCourse->progress }}%
                                    </p>
                                    <p class="text-xs hidden sm:block">Selesai</p>
                                </div>
                            </div>
                            <div class="w-full bg-blue-400/30 rounded-full h-2 mb-3 sm:mb-4">
                                <div
                                    class="bg-white rounded-full h-2 transition-all duration-300"
                                    style="width: {{ $lastCourse->progress }}%"
                                ></div>
                            </div>
                            <a
                                href="{{ route('user.course.my-course.detail', $lastCourse->slug) }}"
                                class="inline-block bg-white text-indigo-600 px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-indigo-50 transition-colors text-sm sm:text-base w-full sm:w-auto text-center"
                            >
                                Lanjutkan →
                            </a>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-6 text-center text-gray-400">
                            <p class="text-sm">Belum ada course yang sedang dipelajari.</p>
                            <a
                                href="{{ route('user.course.my-course') }}"
                                class="inline-block mt-3 text-indigo-600 text-sm font-medium hover:underline"
                            >
                                Lihat semua kursus →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4 sm:space-y-6">
                <!-- My Courses -->
                <div class="sm:bg-white rounded-lg p-3 sm:p-6 sm:shadow-sm">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Kursus Saya</h2>
                        <a
                            href="{{ route('user.course.my-course') }}"
                            class="text-xs text-indigo-600 hover:underline"
                        >
                            Lihat semua →
                        </a>
                    </div>

                    <div class="space-y-3 sm:space-y-4">
                        @forelse ($recentCourses as $course)
                            <a
                                href="{{ route('user.course.my-course.detail', $course->slug) }}"
                                class="block bg-white sm:bg-transparent border border-gray-200 rounded-lg p-3 sm:p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_-8px_rgba(99,102,241,0.3)] hover:border-indigo-500"
                            >
                                <div class="flex gap-3 sm:gap-4">
                                    <img
                                        src="{{ $course->thumbnail_url ?? 'https://picsum.photos/seed/' . $course->id . '/120/80' }}"
                                        alt="{{ $course->name }}"
                                        class="w-14 h-14 sm:w-20 sm:h-20 rounded-lg object-cover flex-shrink-0"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="min-w-0 flex-1">
                                                <h3
                                                    class="font-semibold text-gray-900 text-sm sm:text-base truncate"
                                                >
                                                    {{ $course->name }}
                                                </h3>
                                                <p class="text-xs text-gray-500">
                                                    {{ $course->pivot->progress }}% selesai
                                                </p>
                                            </div>
                                            @if ($course->pivot->status === 'completed')
                                                <x-badge color="emerald" class="hidden sm:inline-flex flex-shrink-0 ml-2">
                                                    Selesai
                                                </x-badge>
                                            @elseif ($course->pivot->status === 'in_progress')
                                                <x-badge color="indigo" class="hidden sm:inline-flex flex-shrink-0 ml-2">
                                                    Sedang Berjalan
                                                </x-badge>
                                            @else
                                                <x-badge color="slate" class="hidden sm:inline-flex flex-shrink-0 ml-2">
                                                    Terdaftar
                                                </x-badge>
                                            @endif
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div
                                                class="{{ $course->pivot->status === 'completed' ? 'bg-emerald-600' : 'bg-indigo-600' }} h-2 rounded-full transition-all duration-300"
                                                style="width: {{ $course->pivot->progress }}%"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-6 text-gray-400">
                                <p class="text-sm">Belum ada kursus yang diikuti.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (! $profile || empty($profile->instansi))
        <!-- Modal Card (Not Blurred) -->
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm"
            style="background-color: rgba(0, 0, 0, 0.3)"
        >
            <div
                class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
            >
                <div class="p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div
                            class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-8 h-8 text-indigo-600"
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
                        <h2 class="text-2xl font-bold text-gray-900">Pilih Instansi Anda</h2>
                        <p class="text-gray-600 mt-2">
                            Lengkapi informasi institusi untuk melanjutkan
                        </p>
                    </div>

                    <!-- Form -->
                    <form
                        id="instansiForm"
                        method="POST"
                        action="{{ route('user.update-instansi') }}"
                        class="space-y-6"
                    >
                        @csrf

                        <!-- Asal Instansi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Asal Instansi
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-3">
                                <label
                                    class="flex-1 flex items-center justify-center px-3 py-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors"
                                >
                                    <input
                                        type="radio"
                                        name="asalInstansi"
                                        value="pusat"
                                        class="mr-2"
                                    />
                                    <span class="font-medium text-sm">Pusat</span>
                                </label>
                                <label
                                    class="flex-1 flex items-center justify-center px-3 py-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors"
                                >
                                    <input
                                        type="radio"
                                        name="asalInstansi"
                                        value="provinsi"
                                        class="mr-2"
                                    />
                                    <span class="font-medium text-sm">Provinsi</span>
                                </label>
                                <label
                                    class="flex-1 flex items-center justify-center px-3 py-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors"
                                >
                                    <input
                                        type="radio"
                                        name="asalInstansi"
                                        value="kabkota"
                                        class="mr-2"
                                    />
                                    <span class="font-medium text-sm">Kab/Kota</span>
                                </label>
                            </div>
                        </div>

                        <!-- Kementerian (Show if Pusat) -->
                        <div id="kementerianSection" class="hidden">
                            <label
                                for="kementerian"
                                class="block text-sm font-medium text-gray-700 mb-2"
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
                                class="block text-sm font-medium text-gray-700 mb-2"
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
                                class="block text-sm font-medium text-gray-700 mb-2"
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
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Instansi
                                <span class="text-red-500">*</span>
                            </label>
                            <select id="instansi" class="w-full">
                                <option value="">Pilih Instansi</option>
                            </select>
                            <p class="mt-2 text-xs text-gray-500 italic">
                                💡 Pilih Lainnya jika Instansi Anda tidak ada
                            </p>
                        </div>

                        <!-- Custom Instansi Input (Show when "Lainnya" is selected) -->
                        <div id="customInstansiSection" class="hidden">
                            <label
                                for="customInstansi"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Nama Instansi
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="instansi_lainnya"
                                id="customInstansi"
                                placeholder="Masukkan nama instansi"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-800 font-medium mb-1">
                                    📝 Cara Penulisan yang Tepat:
                                </p>
                                <ul class="text-xs text-blue-700 space-y-1 ml-4 list-disc">
                                    <li>
                                        Gunakan huruf kapital pada awal setiap kata (Title Case)
                                    </li>
                                    <li>
                                        Contoh:
                                        <span class="font-semibold">
                                            "Dinas Pendidikan dan Kebudayaan"
                                        </span>
                                    </li>
                                    <li>Hindari singkatan kecuali nama resmi menggunakannya</li>
                                    <li>Pastikan ejaan sesuai dengan nama resmi instansi</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Unit Kerja -->
                        <div id="unitKerjaSection" class="hidden">
                            <label
                                for="unitKerja"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Unit Kerja
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="unit_kerja"
                                id="unitKerja"
                                placeholder="Contoh: Bagian SDM"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition-colors flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            Simpan & Lanjutkan
                        </button>
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
                                // Fetch Kab/Kota data via AJAX
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
