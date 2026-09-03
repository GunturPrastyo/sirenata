<x-dashboard::layouts.dashboard title="Edit Proyek - E-Learning">
    @push('styles')
        @include('project::partials.create-styles')
    @endpush

    @php
        $placeholderPrefix = str_contains($routePrefix, 'pusat') ? 'RTKN' : 'RTKD';
    @endphp

    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Proyek', 'url' => route($routePrefix . 'index')], ['label' => 'Edit Proyek']]" />

        <div class="bg-white rounded-lg border border-slate-100 shadow-sm p-6 sm:p-8 max-w-full mx-auto">
            <div class="mb-6 sm:mb-8 border-b border-slate-100 pb-5 sm:pb-6">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Edit Proyek</h1>
                <x-validation-errors class="mt-4" />
            </div>

            <form action="{{ route($routePrefix . 'update', $project->id) }}" method="POST" class="space-y-4 sm:space-y-6">
                @csrf
                @method('PUT')
                <x-form.input name="proyekName" label="Nama Proyek" required value="{{ old('proyekName', $project->name) }}" placeholder="Contoh: {{ $placeholderPrefix }} Sektor Industri Manufaktur 2025" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <x-form.input type="date" id="startDate" name="startDate" label="Tanggal Mulai" required value="{{ old('startDate', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}" onchange="calculateDuration()" />
                    <x-form.input type="date" id="endDate" name="endDate" label="Tanggal Selesai" required value="{{ old('endDate', $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '') }}" onchange="calculateDuration()" />
                </div>

                <!-- Indikator Durasi Otomatis (Read-Only) -->
                <input type="hidden" id="duration" name="duration" value="{{ old('duration', $project->duration) }}">
                <div class="bg-indigo-50/50 border border-indigo-100 rounded-lg p-3 text-sm text-slate-700 font-medium flex items-center">
                    <i class="fas fa-clock text-indigo-500 mr-2 text-lg"></i> 
                    Estimasi Durasi Proyek: &nbsp;<span id="durationText" class="font-extrabold text-indigo-700 text-base">{{ $project->duration ?? 0 }} Bulan</span>
                </div>

                <x-form.select id="teamLeader" name="teamLeader" label="Ketua Tim" required>
                    <option value="">Pilih Ketua Tim</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected((old('teamLeader') ?? $project->team_leader) == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-form.select>

                <!-- Pilihan Anggota Tim (Diperbesar & Dipercantik) -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Anggota Tim (Opsional)</label>
                    <select id="teamMembers" name="teamMembers[]" multiple class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-40 p-2 text-sm">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" class="py-1.5 px-3 rounded-md hover:bg-indigo-50 mb-1 cursor-pointer" @selected(in_array($user->id, old('teamMembers') ?? (is_array($project->team_members) ? $project->team_members : json_decode($project->team_members, true) ?? [])))>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i> Tahan tombol <b>Ctrl</b> (Windows) atau <b>Cmd</b> (Mac) saat mengklik untuk memilih lebih dari satu nama.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4 border-t border-slate-100">
                    <x-button :href="route($routePrefix . 'index')" variant="secondary" class="flex-1">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" class="flex-1">
                        Simpan Perubahan
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