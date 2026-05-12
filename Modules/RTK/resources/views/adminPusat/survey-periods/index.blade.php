<x-dashboard::layouts.dashboard title="Periode Survei RTK Daerah">
    <div class="p-2 sm:p-6">

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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Periode Survei RTK Daerah</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="mb-4 bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-200 flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-lg border border-red-200 flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-600 p-4 rounded-lg border border-red-200">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar Periode Survei</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total: <span class="font-medium text-slate-700">{{ $periods->total() }}</span> Periode
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" x-data @click="$dispatch('open-modal', 'create-survey-period')"
                        class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Tambah Periode</span>
                        <span class="sm:hidden">Tambah</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">
                            <th class="px-4 md:px-6 py-3 text-center w-16">No</th>
                            <th class="px-4 md:px-6 py-3 text-left">Nama Periode</th>
                            <th class="px-4 md:px-6 py-3 text-center w-24">Tahun</th>
                            <th class="px-4 md:px-6 py-3 text-left w-48">Tanggal Pengisian</th>
                            <th class="px-4 md:px-6 py-3 text-center w-28">Status</th>
                            <th class="px-4 md:px-6 py-3 text-left">Deskripsi</th>
                            <th class="px-4 md:px-6 py-3 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($periods as $key => $period)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3 text-center text-slate-600">
                                    {{ $key + $periods->firstItem() }}
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="font-medium text-slate-800">{{ $period->nama }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <span class="font-semibold text-slate-700">{{ $period->tahun }}</span>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    @if ($period->tanggal_mulai && $period->tanggal_selesai)
                                        <span class="text-slate-600">
                                            {{ $period->tanggal_mulai->format('d M Y') }}
                                            <span class="mx-1 text-slate-400">–</span>
                                            {{ $period->tanggal_selesai->format('d M Y') }}
                                        </span>
                                    @elseif ($period->tanggal_mulai)
                                        <span class="text-slate-600">{{ $period->tanggal_mulai->format('d M Y') }} – <span class="italic text-slate-400">Belum ditentukan</span></span>
                                    @else
                                        <span class="text-slate-400 italic">Belum diatur</span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 border rounded-full text-xs font-semibold {{ $period->status_color }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $period->status === 'aktif' ? 'bg-emerald-500' : ($period->status === 'tutup' ? 'bg-red-500' : 'bg-slate-400') }}"></span>
                                        {{ $period->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600 text-xs truncate max-w-[200px]" title="{{ $period->deskripsi }}">
                                        {{ $period->deskripsi ?: '-' }}
                                    </p>
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>

                                        <li>
                                            <button type="button" x-data
                                                @click="$dispatch('open-modal', 'edit-survey-period-{{ $period->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-indigo-600 cursor-pointer">
                                                Edit
                                            </button>
                                        </li>

                                        @if ($period->status !== 'aktif')
                                            <li>
                                                <form action="{{ route('admin-pusat.survey-periods.activate', $period->id) }}" method="POST"
                                                    onsubmit="return confirm('Aktifkan periode ini? Periode aktif lainnya akan otomatis ditutup.');"
                                                    class="inline-flex items-center w-full hover:bg-slate-100 rounded m-0 p-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-left p-2 text-emerald-600">
                                                        Aktifkan
                                                    </button>
                                                </form>
                                            </li>
                                        @endif

                                        @if ($period->status === 'aktif')
                                            <li>
                                                <form action="{{ route('admin-pusat.survey-periods.close', $period->id) }}" method="POST"
                                                    onsubmit="return confirm('Tutup periode ini?');"
                                                    class="inline-flex items-center w-full hover:bg-slate-100 rounded m-0 p-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-left p-2 text-amber-600">
                                                        Tutup
                                                    </button>
                                                </form>
                                            </li>
                                        @endif

                                        <li>
                                            <form action="{{ route('admin-pusat.survey-periods.destroy', $period->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini?');"
                                                class="inline-flex items-center w-full hover:bg-slate-100 rounded m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left p-2 text-red-600">
                                                    Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </x-table.action>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-slate-500">Belum ada periode survei. Klik tombol "Tambah Periode" untuk membuat periode baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $periods->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <x-modal name="create-survey-period" title="Tambah Periode Survei">
        <form action="{{ route('admin-pusat.survey-periods.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="create-nama" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Periode <span class="text-red-500">*</span>
                </label>
                <input type="text" id="create-nama" name="nama" required value="{{ old('nama') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                    placeholder="mis. Survei RTK Daerah 2026">
            </div>

            <div>
                <label for="create-tahun" class="block text-sm font-medium text-gray-700 mb-1">
                    Tahun <span class="text-red-500">*</span>
                </label>
                <input type="number" id="create-tahun" name="tahun" required min="2000" max="2100"
                    value="{{ old('tahun', date('Y')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                    placeholder="2026">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="create-tanggal-mulai" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Mulai
                    </label>
                    <input type="date" id="create-tanggal-mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
                <div>
                    <label for="create-tanggal-selesai" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Selesai
                    </label>
                    <input type="date" id="create-tanggal-selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
            </div>

            <div>
                <label for="create-deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea id="create-deskripsi" name="deskripsi" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                    placeholder="Keterangan singkat tentang periode survei ini...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" x-data @click="$dispatch('close-modal', 'create-survey-period')"
                    class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm text-center cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors text-sm cursor-pointer">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>

    @foreach($periods as $period)
        <x-modal name="edit-survey-period-{{ $period->id }}" title="Edit Periode Survei">
            <form action="{{ route('admin-pusat.survey-periods.update', $period->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit-nama-{{ $period->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Periode <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit-nama-{{ $period->id }}" name="nama" required
                        value="{{ old('nama', $period->nama) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit-tanggal-mulai-{{ $period->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Mulai
                        </label>
                        <input type="date" id="edit-tanggal-mulai-{{ $period->id }}" name="tanggal_mulai"
                            value="{{ old('tanggal_mulai', $period->tanggal_mulai?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label for="edit-tanggal-selesai-{{ $period->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Selesai
                        </label>
                        <input type="date" id="edit-tanggal-selesai-{{ $period->id }}" name="tanggal_selesai"
                            value="{{ old('tanggal_selesai', $period->tanggal_selesai?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <div>
                    <label for="edit-deskripsi-{{ $period->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                        Deskripsi <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea id="edit-deskripsi-{{ $period->id }}" name="deskripsi" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">{{ old('deskripsi', $period->deskripsi) }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" x-data @click="$dispatch('close-modal', 'edit-survey-period-{{ $period->id }}')"
                        class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm text-center cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors text-sm cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-dashboard::layouts.dashboard>
