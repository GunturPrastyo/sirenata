<x-dashboard::layouts.dashboard title="Laporan RTK">
    <div class="p-2 sm:p-6">
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-kab-kota.dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Laporan RTK</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if ($rtkKabKotaActive && $rtkKabKotaActive->isExpired())
            <div class="mb-4 rounded-lg bg-yellow-50 border border-yellow-300 p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z" />
                    </svg>

                    <div>
                        <p class="font-semibold text-yellow-800">
                            RTK telah melewati masa berlaku
                        </p>
                        <p class="text-sm text-yellow-700">
                            Periode RTK berakhir pada tahun
                            <strong>{{ $rtkKabKotaActive->end_date }}</strong>.
                            Silakan segera menyusun RTK terbaru.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (!$rtkKabKotaActive)
            <div class="mb-4 rounded-lg bg-blue-50 border border-blue-300 p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    </svg>

                    <div>
                        <p class="font-semibold text-blue-800">
                            Belum terdapat RTK Kabupaten/Kota
                        </p>
                        <p class="text-sm text-blue-700 mb-3">
                            Saat ini belum ada RTK yang berstatus berlaku.
                            Silakan membuat RTK baru untuk periode berjalan.
                        </p>

                        <a href="{{ route('admin-kab-kota.rtkd.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                            + Buat RTK Baru
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Left Column (6/12) -->
            <div class="space-y-4 sm:space-y-6">
                <!-- RTK Status Card -->
                <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ $rtkKabKotaActive->regency?->name ?? '' }}
                    </h2>
                    <p class="text-gray-600 mb-4">Kabupaten/Kota Administrasi</p>

                    @if ($rtkKabKotaActive)
                        <div
                            class="inline-flex items-center px-4 py-2 
                            {{ $rtkKabKotaActive->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} 
                            rounded-lg">

                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="font-semibold">
                                    RTK {{ $rtkKabKotaActive->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>

                                <p class="text-xs">
                                    Status:
                                    <span
                                        class="px-2 py-1 text-xs rounded-full font-semibold
                                        {{ $rtkKabKotaActive?->status?->color() }}">
                                        {{ $rtkKabKotaActive?->status?->label() }}
                                    </span>

                                    @if ($rtkKabKotaActive->end_date)
                                        hingga {{ \Carbon\Carbon::parse($rtkKabKotaActive->end_date)->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 rounded-lg">
                            <span class="font-semibold">RTK Belum Tersedia</span>
                        </div>
                    @endif
                </div>

                <!-- Masa Berlaku RTK -->
                <div class="bg-white rounded-lg p-4 sm:p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Masa Berlaku RTK</h3>

                    <!-- Date Cards -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-1">Tanggal Mulai</p>
                            <p class="text-base font-bold text-gray-900">1 Januari {{ $rtkKabKotaActive?->start_date }}
                            </p>
                        </div>

                        <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-1">Tanggal Berakhir</p>
                            <p class="text-base font-bold text-gray-900">31 Desember {{ $rtkKabKotaActive?->end_date }}
                            </p>
                        </div>
                    </div>

                    <!-- Period Summary -->
                    <div
                        class="p-5 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg border-l-4 border-indigo-600">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Periode Berlaku</p>
                                <p class="text-xl sm:text-3xl font-bold text-indigo-600">
                                    {{ $rtkKabKotaActive?->start_date }}
                                    - {{ $rtkKabKotaActive?->end_date }}</p>
                            </div>
                            <div class="text-right">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-white rounded-full shadow-md">
                                    <div class="text-center">
                                        <p class="text-lg sm:text-2xl font-bold text-indigo-600">5</p>
                                        <p class="text-xs text-gray-600">Tahun</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column (6/12) - PDF Viewer -->
            <div class="space-y-4 sm:space-y-6">
                <div class="bg-white rounded-lg p-6 shadow-sm sticky top-20">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900">Dokumen RTK</h3>
                        @if ($rtkKabKotaActive)
                            <a href="{{ Storage::url($rtkKabKotaActive->document_path) }}"
                                download="{{ $rtkKabKotaActive->name }}"
                                class="inline-flex items-center px-2 sm:px-3 py-1.5 sm:py-2 bg-indigo-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download
                            </a>
                        @endif
                    </div>
                    @if ($rtkKabKotaActive)
                        <div class="border rounded-lg overflow-hidden bg-gray-100" style="height: 400px;"
                            class="sm:!h-[700px]">

                            <iframe src="{{ Storage::url($rtkKabKotaActive->document_path) }}" class="w-full h-full"
                                frameborder="0"></iframe>
                        </div>
                    @else
                        <div class="flex items-center justify-center" style="height: 400px;" class="sm:!h-[700px]">
                            <p>Belum ada RTK yang diupload</p>
                        </div>
                    @endif
                </div>
                <div class="mt-3 flex items-center text-sm text-gray-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    RTK_{{ $rtkKabKotaActive?->regency->name }}_{{ $rtkKabKotaActive?->start_date }}-
                    {{ $rtkKabKotaActive?->end_date }}.pdf
                </div>
            </div>
        </div>
    </div>

</x-dashboard::layouts.dashboard>
