<x-dashboard::layouts.dashboard title="Validasi RTK Provinsi">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-pusat.dashboard') }}"
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
                        <a href="{{ route('admin-pusat.rtkd.index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Daftar Laporan
                            RTKD Provinsi</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Daftar Laporan RTK
                            {{ $province->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 w-full lg:flex-1">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            Status Verifikasi
                        </label>
                        <div class="relative">
                            <i
                                class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"
                            ></i>
                            <select
                                name="status_verification"
                                onchange="this.form.submit()"
                                class="pl-8 pr-3 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Semua</option>
                                @foreach (\Modules\RTK\Enums\RTKStatusVerification::cases() as $statusVerifikasi)
                                    <option
                                        value="{{ $statusVerifikasi->value }}"
                                        @selected(request('status_verification') === $statusVerifikasi->value)
                                    >
                                        {{ $statusVerifikasi->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            Status Dokumen
                        </label>
                        <div class="relative">
                            <i
                                class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"
                            ></i>
                            <select
                                name="status_document"
                                onchange="this.form.submit()"
                                class="pl-8 pr-3 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Semua</option>
                                @foreach (\Modules\RTK\Enums\StatusDocument::cases() as $statusDocument)
                                    <option
                                        value="{{ $statusDocument->value }}"
                                        @selected(request('status_document') === $statusDocument->value)
                                    >
                                        {{ $statusDocument->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Filter RTK Acuan (is_active) --}}
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            RTK Acuan
                        </label>
                        <div class="relative">
                            <i
                                class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"
                            ></i>
                            <select
                                name="acuan"
                                onchange="this.form.submit()"
                                class="pl-8 pr-3 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Semua</option>
                                <option value="1" @selected(request('acuan') === '1')>
                                    Ya (Acuan)
                                </option>
                                <option value="0" @selected(request('acuan') === '0')>
                                    Tidak
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Per Page --}}
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-medium text-slate-500 mb-1">
                            Tampilkan
                        </label>
                        <select
                            name="per_page"
                            onchange="this.form.submit()"
                            class="px-3 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            @foreach ([10, 20, 50, 100] as $page)
                                <option
                                    value="{{ $page }}"
                                    {{ request('per_page') == $page ? 'selected' : '' }}
                                >
                                    {{ $page }} / Halaman
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Search + Buttons --}}
                <div class="flex gap-2 w-full lg:w-72 shrink-0">
                    <div class="relative flex-1">
                        <i
                            class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"
                        ></i>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama RTK..."
                            class="pl-9 pr-4 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        />
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-4 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition shrink-0"
                    >
                        <i class="fas fa-search text-xs"></i>
                    </button>

                    <a
                        href="{{ route('admin-pusat.rtkd.show-province', $provinceCode) }}"
                        class="inline-flex items-center gap-2 px-4 rounded-md border border-slate-300 text-slate-600 text-sm font-medium hover:bg-slate-100 transition shrink-0"
                    >
                        <i class="fas fa-rotate-left text-xs"></i>
                    </a>
                </div>
            </div>
        </form>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div
                class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar Laporan RTK {{ $province?->name }} </h2>
                </div>

                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('admin-pusat.rtkd.show-province-export', $provinceCode) }}?{{ http_build_query(request()->only(['search', 'status_document', 'status_verification', 'acuan'])) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-download text-xs"></i>
                        <span class="hidden sm:inline">Ekspor</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">

                            <th class="px-4 md:px-6 py-3 text-left">No.</th>
                            <th class="px-4 md:px-6 py-3 text-left">Dokumen RTK</th>
                            <th class="px-4 md:px-6 py-3 text-left">Periode Berlaku</th>
                            <th class="px-4 md:px-6 py-3 text-left">Status Verifikasi</th>
                            <th class="px-4 md:px-6 py-3 text-left">Status Dokumen</th>
                            <th class="px-4 md:px-6 py-3 text-left">RTK Acuan</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="admin-table-body" class="divide-y divide-slate-200">
                        @forelse ($rtks as $key => $rtk)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $key + $rtks->firstItem() }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $rtk->name }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $rtk->start_date }} - {{ $rtk->end_date }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 ">
                                    <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $rtk->status_verification->color() }}">
                                        {{ $rtk->status_verification->label() }}
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-3 ">
                                    <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $rtk->status_document->color() }}">
                                        {{ $rtk->status_document->label() }}
                                    </span>
                                </td>

                                <td class="px-4 md:px-6 py-3">
                                    @if ($rtk->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Ya
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6L18 18M6 18L18 6" />
                                            </svg>
                                            Tidak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        {{-- Step 1: Approve verifikasi — muncul kalau PENDING + is_active --}}
                                        @if($rtk->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING && $rtk->is_active)
                                            <li class="mb-2">
                                                <span class="px-3 py-2 text-center text-xs text-gray-400">Status Verifikasi</span>
                                                <div class="h-px my-1 -mx-1 bg-neutral-200"></div>
                                                <button type="button" x-data
                                                    @click="$dispatch('open-modal', 'approve-rtk-{{ $rtk->id }}')"
                                                    class="w-full px-3 py-2 cursor-pointer text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                                    Setujui Verifikasi
                                                </button>

                                                <x-modal name="approve-rtk-{{ $rtk->id }}" title="Konfirmasi Persetujuan RTK"
                                                    maxWidth="sm:max-w-md">
                                                    <div class="p-6 text-center">
                                                        <div class="flex justify-center mb-4">
                                                            <div
                                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100">
                                                                <svg class="w-6 h-6 text-green-600"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Setujui Verifikasi
                                                            RTK</h3>
                                                        <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menyetujui
                                                            verifikasi dokumen berikut?</p>
                                                        <div
                                                            class="bg-gray-50 border rounded-md p-3 text-sm text-gray-600 mb-6">
                                                            <p class="font-medium">{{ $rtk->name }}</p>
                                                            <p class="text-xs text-gray-500">Periode {{ $rtk->start_date }} -
                                                                {{ $rtk->end_date }}</p>
                                                        </div>
                                                        <form
                                                            action="{{ route('admin-pusat.rtkd.approveVerificationProvince', $rtk->id) }}"
                                                            method="POST" class="flex justify-center gap-3">
                                                            @csrf
                                                            <button type="submit"
                                                                class="px-4 py-2 cursor-pointer text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                                                Ya, Setujui
                                                            </button>
                                                            <button type="button"
                                                                @click="$dispatch('close-modal', 'approve-rtk-{{ $rtk->id }}')"
                                                                class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                                                Batal
                                                            </button>
                                                        </form>
                                                    </div>
                                                </x-modal>
                                            </li>
                                        @endif

                                        {{-- Step 2: Approve dokumen — muncul kalau status_verification APPROVED +
                                        status_document NA --}}
                                        @if(
                                                $rtk->status_verification === \Modules\RTK\Enums\RTKStatusVerification::APPROVED &&
                                                $rtk->status_document === \Modules\RTK\Enums\StatusDocument::NA &&
                                                $rtk->is_active
                                            )
                                            <li class="mb-2">
                                                <span class="px-3 py-2 text-center text-xs text-gray-400">Status Dokumen</span>
                                                <div class="h-px my-1 -mx-1 bg-neutral-200"></div>
                                                <button type="button" x-data
                                                    @click="$dispatch('open-modal', 'approve-doc-rtk-{{ $rtk->id }}')"
                                                    class="w-full px-3 py-2 cursor-pointer text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                                    Setujui Dokumen
                                                </button>

                                                <x-modal name="approve-doc-rtk-{{ $rtk->id }}"
                                                    title="Konfirmasi Persetujuan Dokumen" maxWidth="sm:max-w-md">
                                                    <div class="p-6 text-center">
                                                        <div class="flex justify-center mb-4">
                                                            <div
                                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                                                                <svg class="w-6 h-6 text-blue-600"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Setujui Dokumen RTK
                                                        </h3>
                                                        <p class="text-sm text-gray-500 mb-4">
                                                            Dengan menyetujui dokumen ini, RTK akan menjadi
                                                            <strong>berlaku</strong>.
                                                        </p>

                                                        {{-- Warning kalau ada RTK berlaku --}}
                                                        @if($rtkBerlaku ?? false)
                                                            <div
                                                                class="bg-amber-50 border border-amber-200 rounded-md p-3 text-sm text-amber-700 mb-4 text-left">
                                                                <p class="font-medium">⚠ Perhatian</p>
                                                                <p class="text-xs mt-1">RTK <strong>{{ $rtkBerlaku->name }}</strong>
                                                                    yang sedang berlaku akan otomatis diubah menjadi EXPIRED.</p>
                                                            </div>
                                                        @endif

                                                        <div
                                                            class="bg-gray-50 border rounded-md p-3 text-sm text-gray-600 mb-6">
                                                            <p class="font-medium">{{ $rtk->name }}</p>
                                                            <p class="text-xs text-gray-500">Periode {{ $rtk->start_date }} -
                                                                {{ $rtk->end_date }}</p>
                                                        </div>
                                                        <form action="{{ route('admin-pusat.rtkd.approveDocumentProvince', $rtk->id) }}"
                                                            method="POST" class="flex justify-center gap-3">
                                                            @csrf
                                                            <button type="submit"
                                                                class="px-4 py-2 cursor-pointer text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                                                Ya, Setujui
                                                            </button>
                                                            <button type="button"
                                                                @click="$dispatch('close-modal', 'approve-doc-rtk-{{ $rtk->id }}')"
                                                                class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                                                Batal
                                                            </button>
                                                        </form>
                                                    </div>
                                                </x-modal>
                                            </li>
                                        @endif

                                        {{-- Tolak — muncul kalau PENDING atau APPROVED tapi belum VALID, dan is_active --}}
                                        @if(
                                                $rtk->is_active && 
                                                $rtk->status_document !== \Modules\RTK\Enums\StatusDocument::VALID && 
                                                $rtk->status_verification !== \Modules\RTK\Enums\RTKStatusVerification::REJECTED
                                            )
                                            <li class="mb-2">
                                                <button type="button" x-data
                                                    @click="$dispatch('open-modal', 'reject-rtk-{{ $rtk->id }}')"
                                                    class="w-full px-3 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                                    Tolak
                                                </button>

                                                <x-modal name="reject-rtk-{{ $rtk->id }}" title="Konfirmasi Penolakan RTK"
                                                    maxWidth="sm:max-w-xl">
                                                    <div class="p-6">
                                                        <div class="flex justify-center mb-4">
                                                            <div
                                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                                                                <svg class="w-6 h-6 text-red-600"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Tolak
                                                            Dokumen RTK</h3>
                                                        <p class="text-sm text-gray-500 text-center mb-6">Silakan berikan alasan
                                                            penolakan dokumen berikut.</p>
                                                        <div
                                                            class="bg-gray-50 border rounded-md p-3 text-sm text-gray-600 mb-5">
                                                            <p class="font-medium">{{ $rtk->name }}</p>
                                                            <p class="text-xs text-gray-500">Periode {{ $rtk->start_date }} -
                                                                {{ $rtk->end_date }}</p>
                                                        </div>
                                                        <form action="{{ route('admin-pusat.rtkd.rejectProvince', $rtk->id) }}"
                                                            method="POST" class="space-y-4">
                                                            @csrf
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-1">Alasan
                                                                    Penolakan</label>
                                                                <textarea name="reason" rows="3" required
                                                                    class="w-full px-3 py-2 text-sm border rounded-md focus:ring focus:ring-red-200 focus:border-red-400"
                                                                    placeholder="Contoh: Dokumen belum sesuai format yang ditetapkan"></textarea>
                                                            </div>
                                                            <div class="flex justify-end gap-3 pt-2">
                                                                <button type="button"
                                                                    @click="$dispatch('close-modal', 'reject-rtk-{{ $rtk->id }}')"
                                                                    class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                                                    Batal
                                                                </button>
                                                                <button type="submit"
                                                                    class="px-4 py-2 cursor-pointer text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                                                    Ya, Tolak
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </x-modal>
                                            </li>
                                            <div class="h-px my-1 -mx-1 bg-neutral-500"></div>
                                        @endif

                                        {{-- Badge RTK Berlaku --}}
                                        @if($rtk->is_berlaku)
                                         
                                            <li>
                                                <span
                                                    class="inline-flex items-center gap-1 w-full justify-center px-3 py-2 text-sm font-medium text-emerald-700 bg-emerald-100 rounded">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    RTK Berlaku
                                                </span>
                                            </li>
                                        @endif
                                        <li class="mb-2">
                                            <button type="button" x-data
                                                @click="$dispatch('open-modal', 'open-document-province-{{ $rtk->id }}')"
                                                class="inline-flex items-center cursor-pointer w-full p-2 hover:bg-slate-100 rounded">
                                                Preview Dokumen RTK
                                            </button>

                                            <x-modal name="open-document-province-{{ $rtk->id }}"
                                                title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                                                <h1>{{ $rtk->name }}</h1>
                                                <div class="border border-gray-300 rounded-md overflow-hidden">
                                                    @if ($rtk->document_path && Storage::disk('public')->exists($rtk->document_path))
                                                        <iframe src="{{ Storage::url($rtk->document_path) }}"
                                                            class="w-full min-h-[500px] rounded-md border"></iframe>
                                                    @else
                                                        <div
                                                            class="flex items-center justify-center min-h-[500px] text-gray-400 border rounded-md">
                                                            Tidak ada dokumen tersimpan
                                                        </div>
                                                    @endif
                                                </div>
                                                <x-slot:footer>
                                                    <button
                                                        @click="$dispatch('close-modal', 'open-document-province-{{ $rtk->id }}')"
                                                        class="inline-flex items-center justify-center px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-100 hover:text-neutral-600 hover:bg-neutral-100">
                                                        Close
                                                    </button>
                                                </x-slot:footer>
                                            </x-modal>
                                        </li>
                                    </x-table.action>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-500">Tidak ada data RTKD Kab/Kota</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $rtks->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>