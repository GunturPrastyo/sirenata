<x-dashboard::layouts.dashboard title="Tambah Draft Proyek - E-Learning">
    @push('styles')
        @include('project::partials.create-styles')
    @endpush

    @php
        $placeholderPrefix = str_contains($routePrefix, 'pusat') ? 'RTKN' : 'RTKD';
    @endphp

    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Proyek', 'url' => route($routePrefix . 'index')], ['label' => 'Tambah Draft Proyek']]" />

        <div class="bg-white rounded-lg border border-slate-100 shadow-sm p-6 sm:p-8 max-w-full mx-auto">
            <div class="mb-6 sm:mb-8 border-b border-slate-100 pb-5 sm:pb-6">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Usulkan Proyek Baru</h1>
                <p class="text-sm text-slate-500 mt-1">Buat draft proyek dan unggah Dokumen SK untuk ditinjau oleh Admin Pusat.</p>
                <x-validation-errors class="mt-4" />
            </div>

            <!-- PERHATIAN: Tambahkan enctype="multipart/form-data" -->
            <form action="{{ route($routePrefix . 'store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
                @csrf
                <x-form.input name="proyekName" label="Nama Proyek" required value="{{ old('proyekName') }}" placeholder="Contoh: {{ $placeholderPrefix }} Sektor Industri Manufaktur 2025" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <x-form.input type="date" id="startDate" name="startDate" label="Tanggal Mulai" required value="{{ old('startDate') }}" onchange="calculateDuration()" />
                    <x-form.input type="date" id="endDate" name="endDate" label="Tanggal Selesai" required value="{{ old('endDate') }}" onchange="calculateDuration()" />
                </div>

                <!-- Indikator Durasi Otomatis (Read-Only) -->
                <input type="hidden" id="duration" name="duration" value="{{ old('duration') }}">
                <div class="bg-indigo-50/50 border border-indigo-100 rounded-lg p-3 text-sm text-slate-700 font-medium flex items-center">
                    <i class="fas fa-clock text-indigo-500 mr-2 text-lg"></i> 
                    Estimasi Durasi Proyek: &nbsp;<span id="durationText" class="font-extrabold text-indigo-700 text-base">0 Bulan</span>
                </div>

                <!-- Input Upload SK Proyek -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Unggah Dokumen SK (PDF) <span class="text-red-500">*</span></label>
                    <input type="file" name="sk_document" accept=".pdf" required 
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-lg p-1.5 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <p class="text-xs text-slate-500 mt-1.5">Format wajib .pdf dengan ukuran maksimal 5 MB.</p>
                </div>

                <!-- Alert Info Alur -->
                <div class="bg-amber-50 border border-amber-200 p-4 rounded-lg flex items-start gap-3 mt-2">
                    <i class="fas fa-info-circle text-amber-600 mt-0.5"></i>
                    <div class="text-sm text-amber-800 leading-relaxed">
                        <strong>Catatan:</strong> Proyek akan tersimpan dengan status <b>Draft</b>. Anda baru dapat memilih Ketua Tim dan Anggota Tim setelah draft disetujui (Diaktifkan) oleh Admin Pusat.
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4 border-t border-slate-100">
                    <x-button :href="route($routePrefix . 'index')" variant="secondary" class="flex-1">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" class="flex-1">
                        Kirim Draft Proyek
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @include('project::partials.create-scripts')
        <script>
            function calculateDuration() {
                const start = document.getElementById('startDate').value;
                const end = document.getElementById('endDate').value;
                const durationInput = document.getElementById('duration');
                const durationText = document.getElementById('durationText');

                if (start && end) {
                    const startDate = new Date(start);
                    const endDate = new Date(end);

                    let months = (endDate.getFullYear() - startDate.getFullYear()) * 12;
                    months += endDate.getMonth() - startDate.getMonth();

                    if (endDate.getDate() < startDate.getDate()) {
                        months--;
                    }

                    months = months <= 0 ? 1 : months; 

                    durationInput.value = months;
                    durationText.innerText = months + ' Bulan';
                } else {
                    durationInput.value = '';
                    durationText.innerText = '0 Bulan';
                }
            }
            document.addEventListener('DOMContentLoaded', calculateDuration);
        </script>
    @endpush
</x-dashboard::layouts.dashboard>