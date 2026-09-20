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
            <div class="mb-6 sm:mb-8 border-b border-slate-100 pb-5 sm:pb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Edit Proyek</h1>
                    <p class="text-sm text-slate-500 mt-1">Perbarui data proyek dan Dokumen SK.</p>
                </div>
                <x-badge color="{{ $project->status == 'On Progress' ? 'emerald' : 'amber' }}" :text="$project->status" />
            </div>
            
            <x-validation-errors class="mb-6" />

            <!-- PERHATIAN: Tambahkan enctype="multipart/form-data" -->
            <form action="{{ route($routePrefix . 'update', $project->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
                @csrf
                @method('PUT')
                <x-form.input name="proyekName" label="Nama Proyek" required value="{{ old('proyekName', $project->name) }}" placeholder="Contoh: {{ $placeholderPrefix }} Sektor Industri Manufaktur 2025" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <x-form.input type="date" id="startDate" name="startDate" label="Tanggal Mulai" required value="{{ old('startDate', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}" onchange="calculateDuration()" />
                    <x-form.input type="date" id="endDate" name="endDate" label="Tanggal Selesai" required value="{{ old('endDate', $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '') }}" onchange="calculateDuration()" />
                </div>

                <input type="hidden" id="duration" name="duration" value="{{ old('duration', $project->duration) }}">
                <div class="bg-indigo-50/50 border border-indigo-100 rounded-lg p-3 text-sm text-slate-700 font-medium flex items-center">
                    <i class="fas fa-clock text-indigo-500 mr-2 text-lg"></i> 
                    Estimasi Durasi Proyek: &nbsp;<span id="durationText" class="font-extrabold text-indigo-700 text-base">{{ $project->duration ?? 0 }} Bulan</span>
                </div>

                <!-- Input Ubah Dokumen SK -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Dokumen SK Proyek</label>
                    @if($project->sk_document)
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $project->sk_document) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 rounded-md text-xs font-bold hover:bg-slate-200 transition border border-slate-200">
                                <i class="fas fa-file-pdf text-red-500"></i> Lihat SK Saat Ini
                            </a>
                        </div>
                    @endif
                    <input type="file" name="sk_document" accept=".pdf" 
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-lg p-1.5 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <p class="text-xs text-slate-500 mt-1.5">Abaikan jika Anda tidak ingin mengubah/mengganti dokumen SK yang ada.</p>
                </div>

                <!-- ============================================== -->
                <!-- KONDISIONAL PEMILIHAN TIM (JIKA SUDAH DISETUJUI) -->
                <!-- ============================================== -->
                @if($project->status === 'On Progress' || $project->status === 'Completed')
                    <div class="border-t border-slate-200 pt-6 mt-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Pengaturan Tim Proyek</h3>
                        
                        @if($project->is_prerequisite_active)
                            <div class="bg-indigo-50 border border-indigo-100 text-indigo-700 p-3 rounded-lg text-sm mb-5 flex items-start gap-2">
                                <i class="fas fa-graduation-cap mt-0.5 text-indigo-500"></i>
                                <span>Pusat mewajibkan prasyarat kursus. <b>Hanya pengguna yang lulus kursus yang muncul di bawah ini.</b></span>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <x-form.select id="teamLeader" name="teamLeader" label="Ketua Tim" required>
                                <option value="">Pilih Ketua Tim</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected((old('teamLeader') ?? $project->team_leader) == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-form.select>

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
                        </div>
                    </div>
                @else
                    <!-- TAMPILAN JIKA MASIH DRAFT -->
                    <div class="border-t border-slate-200 pt-6 mt-6">
                        <div class="bg-amber-50 border border-amber-200 p-4 rounded-lg flex items-start gap-3">
                            <i class="fas fa-clock text-amber-600 mt-0.5"></i>
                            <div class="text-sm text-amber-800 leading-relaxed">
                                <strong>Menunggu Persetujuan Pusat</strong><br>
                                Form pemilihan Ketua dan Anggota Tim dikunci dan akan muncul otomatis pada halaman ini setelah Admin Pusat menyetujui dan mengaktifkan proyek ini.
                            </div>
                        </div>
                    </div>
                @endif
                <!-- ============================================== -->

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-slate-100">
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