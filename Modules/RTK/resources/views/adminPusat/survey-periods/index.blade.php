<x-dashboard::layouts.dashboard title="Periode Survei RTK Daerah">
    <div class="p-2 sm:p-6 pt-6 sm:pt-8">
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[['label' => 'Periode Survei RTK Daerah']]" />

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div
                class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar Periode Survei</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total: <span class="font-medium text-slate-700">{{ $periods->total() }}</span> Periode
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <x-button type="button" x-data @click="$dispatch('open-modal', 'create-survey-period')" icon="fas fa-plus">
                        Tambah Periode
                    </x-button>
                </div>
            </div>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th>Nama</x-table.th>
                        <x-table.th align="center" class="w-24">Tahun</x-table.th>
                        <x-table.th class="w-40">Tanggal Mulai</x-table.th>
                        <x-table.th class="w-40">Tanggal Selesai</x-table.th>
                        <x-table.th align="center" class="w-32">Total Verifikasi</x-table.th>
                        <x-table.th align="center" class="w-28">Status</x-table.th>
                        <x-table.th align="center" class="w-24">Aksi</x-table.th>
                    </tr>
                </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($periods as $key => $period)
                            <tr class="hover:bg-slate-50 transition">
                                <x-table.td>
                                    <p class="font-medium text-slate-800">{{ $period->nama }}</p>
                                </x-table.td>
                                <x-table.td align="center">
                                    <span class="font-semibold text-slate-700">{{ $period->tahun }}</span>
                                </x-table.td>
                                <x-table.td>
                                    @if ($period->tanggal_mulai)
                                        <span class="text-slate-600">
                                            {{ $period->tanggal_mulai->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Belum diatur</span>
                                    @endif
                                </x-table.td>
                                <x-table.td>
                                    @if ($period->tanggal_selesai)
                                        <span class="text-slate-600">
                                            {{ $period->tanggal_selesai->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Belum diatur</span>
                                    @endif
                                </x-table.td>
                                <x-table.td align="center">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg font-bold text-sm">
                                        {{ $period->submissions_count }}
                                        <span class="text-[10px] uppercase tracking-wider font-medium text-indigo-400">Provinsi</span>
                                    </div>
                                </x-table.td>
                                <x-table.td align="center">
                                    @php
                                        $statusColor = match($period->status) {
                                            'aktif' => 'success',
                                            'tutup' => 'danger',
                                            default => 'slate'
                                        };
                                    @endphp
                                    <x-badge :color="$statusColor" :text="$period->status_label" />
                                </x-table.td>
                                <x-table.td align="center">
                                    <x-table.action>
                                        <li>
                                            <button x-data x-on:click="$dispatch('open-modal', 'show-survey-period-{{ $period->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-blue-600 cursor-pointer">
                                                Detail
                                            </button>
                                        </li>

                                        @if ($period->status !== 'aktif')
                                            <li>
                                                <form action="{{ route('admin-pusat.survey-periods.activate', $period->id) }}"
                                                    method="POST"
                                                    class="inline-flex items-center w-full hover:bg-slate-100 rounded m-0 p-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-left p-2 text-emerald-600">
                                                        Aktifkan
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li>
                                                <form action="{{ route('admin-pusat.survey-periods.close', $period->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Tutup periode ini?');"
                                                    class="inline-flex items-center w-full hover:bg-slate-100 rounded m-0 p-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full text-left p-2 text-red-600">
                                                        Tutup
                                                    </button>

                                                </form>
                                            </li>
                                        @endif

                                        <li>
                                            <button type="button" x-data
                                                @click="$dispatch('open-modal', 'edit-survey-period-{{ $period->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-amber-600 cursor-pointer">
                                                Ubah
                                            </button>
                                        </li>

                                        @if($period->submissions_count > 0)
                                        <li>
                                            <button type="button" x-data
                                                @click="$dispatch('open-modal', 'copy-submissions-{{ $period->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-teal-600 cursor-pointer">
                                                Salin Data
                                            </button>
                                        </li>
                                        @endif

                                        <li>
                                            <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                <x-modal-delete :id="'delete-period-' . $period->id"
                                                    message="Apakah Anda yakin ingin menghapus periode survei ini?"
                                                    :item-name="$period->nama" buttonText="Hapus"
                                                    buttonClass="w-full text-left text-red-600 outline-none cursor-pointer"
                                                    :route="route('admin-pusat.survey-periods.destroy', $period->id)" />
                                            </div>
                                        </li>
                                    </x-table.action>
                                </x-table.td>
                            </tr>
                        @empty
                            <tr>
                                <x-table.td colspan="7" align="center" class="py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-slate-500">Belum ada periode survei. Klik tombol "Tambah Periode"
                                        untuk membuat periode baru.</p>
                                </x-table.td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-table.table>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $periods->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <x-modal name="create-survey-period" title="Tambah Periode Survei">
        <form action="{{ route('admin-pusat.survey-periods.store') }}" method="POST" class="space-y-4">
            @csrf
            <x-form.input name="nama" label="Nama Periode" required placeholder="mis. Survei RTK Daerah 2026" />
            <x-form.input type="number" name="tahun" label="Tahun" required min="2000" max="2100" :value="date('Y')" placeholder="2026" />

            <div class="grid grid-cols-2 gap-4">
                <x-form.input type="date" name="tanggal_mulai" label="Tanggal Mulai" />
                <x-form.input type="date" name="tanggal_selesai" label="Tanggal Selesai" />
            </div>

            <x-form.textarea name="deskripsi" label="Deskripsi (opsional)" rows="3" placeholder="Keterangan singkat tentang periode survei ini..." />

            <div class="flex gap-3 pt-2">
                <x-button type="button" variant="white" class="flex-1" x-data @click="$dispatch('close-modal', 'create-survey-period')">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" class="flex-1">
                    Simpan
                </x-button>
            </div>
        </form>
    </x-modal>

    @foreach($periods as $period)
        <x-modal name="edit-survey-period-{{ $period->id }}" title="Edit Periode Survei">
            <form action="{{ route('admin-pusat.survey-periods.update', $period->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-form.input name="nama" id="edit-nama-{{ $period->id }}" label="Nama Periode" required :value="$period->nama" />

                <div class="grid grid-cols-2 gap-4">
                    <x-form.input type="date" name="tanggal_mulai" id="edit-tanggal-mulai-{{ $period->id }}" label="Tanggal Mulai" :value="$period->tanggal_mulai?->format('Y-m-d')" />
                    <x-form.input type="date" name="tanggal_selesai" id="edit-tanggal-selesai-{{ $period->id }}" label="Tanggal Selesai" :value="$period->tanggal_selesai?->format('Y-m-d')" />
                </div>

                <x-form.textarea name="deskripsi" id="edit-deskripsi-{{ $period->id }}" label="Deskripsi (opsional)" rows="3" :value="$period->deskripsi" />

                <div class="flex gap-3 pt-2">
                    <x-button type="button" variant="white" class="flex-1" x-data @click="$dispatch('close-modal', 'edit-survey-period-{{ $period->id }}')">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" class="flex-1">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </x-modal>
    @endforeach

    @foreach($periods as $period)
        @if($period->submissions_count > 0)
        <x-modal name="copy-submissions-{{ $period->id }}" title="Salin Data Terverifikasi">
            <form action="{{ route('admin-pusat.survey-periods.copy-submissions', $period->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold">Sumber:</span> {{ $period->nama }} ({{ $period->tahun }})
                    </p>
                    <p class="text-sm text-blue-600 mt-1">
                        {{ $period->submissions_count }} data terverifikasi akan disalin ke periode tujuan.
                    </p>
                </div>

                <x-form.select name="target_period_id" id="target-period-{{ $period->id }}" label="Periode Tujuan" required>
                    <option value="">-- Pilih Periode Tujuan --</option>
                    @foreach($allPeriods as $targetPeriod)
                        @if($targetPeriod->id !== $period->id)
                            <option value="{{ $targetPeriod->id }}">
                                {{ $targetPeriod->nama }} ({{ $targetPeriod->tahun }}) — {{ $targetPeriod->status_label }}
                            </option>
                        @endif
                    @endforeach
                </x-form.select>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                    <p class="text-xs text-amber-700">
                        <strong>Catatan:</strong> Data yang sudah ada di periode tujuan akan dilewati (tidak ditimpa).
                    </p>
                </div>

                <div class="flex gap-3 pt-2">
                    <x-button type="button" variant="white" class="flex-1" x-data @click="$dispatch('close-modal', 'copy-submissions-{{ $period->id }}')">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="success" class="flex-1">
                        Salin Data
                    </x-button>
                </div>
            </form>
        </x-modal>
        @endif
    @endforeach
</x-dashboard::layouts.dashboard>