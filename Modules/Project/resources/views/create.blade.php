<x-dashboard::layouts.dashboard title="Tambah Proyek - E-Learning">
    @push('styles')
        @include('project::partials.create-styles')
    @endpush

    @php
        $placeholderPrefix = str_contains($routePrefix, 'pusat') ? 'RTKN' : 'RTKD';
    @endphp

    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Proyek', 'url' => route($routePrefix . 'index')], ['label' => 'Tambah Proyek']]" />

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 max-w-2xl mx-auto">
            <div class="mb-6 sm:mb-8 border-b border-slate-100 pb-5 sm:pb-6">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Proyek Baru</h1>

                <x-validation-errors class="mt-4" />
            </div>

            <form action="{{ route($routePrefix . 'store') }}" method="POST" class="space-y-4 sm:space-y-6">
                @csrf
                <x-form.input name="proyekName" label="Nama Proyek" required value="{{ old('proyekName') }}" placeholder="Contoh: {{ $placeholderPrefix }} Sektor Industri Manufaktur 2025" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <x-form.input type="date" name="startDate" label="Tanggal Mulai" required value="{{ old('startDate') }}" />
                    <x-form.input type="date" name="endDate" label="Tanggal Selesai" required value="{{ old('endDate') }}" />
                </div>

                <x-form.input type="number" name="duration" label="Durasi (Bulan)" placeholder="Contoh: 12" value="{{ old('duration') }}" />

                <x-form.select id="teamLeader" name="teamLeader" label="Ketua Tim" required>
                    <option value="">Pilih Ketua Tim</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('teamLeader') == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-form.select>

                <x-form.select id="teamMembers" name="teamMembers[]" label="Anggota Tim (Opsional)" multiple>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(in_array($user->id, old('teamMembers') ?? []))>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </x-form.select>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
                    <x-button :href="route($routePrefix . 'index')" variant="secondary" class="flex-1">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" class="flex-1">
                        Tambah Proyek
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @include('project::partials.create-scripts')
    @endpush
</x-dashboard::layouts.dashboard>