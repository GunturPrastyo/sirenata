<x-dashboard::layouts.dashboard title="Persetujuan Proyek Daerah">
    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Proyek', 'url' => route($routePrefix . 'index')], ['label' => 'Tinjau & Setujui']]" />

        <div class="bg-white rounded-lg border border-slate-100 shadow-sm p-6 sm:p-8 max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-6 border-b border-slate-100 pb-5">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Tinjau & Setujui Proyek</h1>
                <p class="text-sm text-slate-500 mt-1">Tinjau draft usulan dari daerah dan tentukan aturan kursus sebelum disetujui.</p>
                <x-validation-errors class="mt-4" />
            </div>

            <!-- BAGIAN 1: Informasi Proyek (Read-Only) -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 mb-8">
                <h3 class="text-sm font-bold text-slate-800 mb-4 border-b border-slate-200 pb-2">Detail Usulan Proyek</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <span class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Nama Proyek</span>
                        <span class="text-sm font-bold text-slate-900">{{ $project->name }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Tipe Wilayah</span>
                        <x-badge color="indigo" :text="$project->type" />
                    </div>
                    <div>
                        <span class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Periode Proyek</span>
                        <span class="text-sm font-medium text-slate-700">
                            {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : '-' }} s/d 
                            {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Surat Keputusan (SK)</span>
                        @if($project->sk_document)
                            <a href="{{ asset('storage/' . $project->sk_document) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-300 shadow-sm text-slate-700 rounded-md text-xs font-bold hover:bg-slate-100 transition">
                                <i class="fas fa-file-pdf text-red-500 text-sm"></i> Buka File SK
                            </a>
                        @else
                            <span class="inline-flex items-center text-xs font-medium text-red-600 bg-red-50 px-2.5 py-1 rounded-md border border-red-100">
                                <i class="fas fa-exclamation-triangle mr-1.5"></i> Belum Ada SK
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: Form Pengaturan (Prasyarat & Status ACC) -->
            <form action="{{ route($routePrefix . 'update-prerequisite', $project->id) }}" method="POST" class="space-y-7">
                @csrf
                @method('PATCH')

                <!-- Atur Prasyarat -->
                <div>
                    <h3 class="text-sm font-bold text-slate-800 mb-3">Pengaturan Prasyarat Anggota Tim</h3>
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 sm:p-5">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <div class="pt-0.5">
                                <input type="checkbox" name="is_prerequisite_active" id="is_prerequisite_active" value="1"
                                    class="w-5 h-5 text-indigo-600 border-indigo-300 rounded focus:ring-indigo-500 transition-colors"
                                    {{ old('is_prerequisite_active', $project->is_prerequisite_active) ? 'checked' : '' }}
                                    onchange="toggleCourseSelection()">
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-slate-900">Wajibkan Prasyarat Kursus LMS</span>
                                <span class="block text-xs text-slate-600 mt-1 leading-relaxed">
                                    Jika diaktifkan, Admin Daerah hanya dapat menugaskan pengguna yang telah <b>Lulus</b> dari kursus spesifik yang Anda tentukan di bawah ini.
                                </span>
                            </div>
                        </label>

                        <div id="courseSelectionDiv" class="mt-4 pt-4 border-t border-indigo-100/60 {{ old('is_prerequisite_active', $project->is_prerequisite_active) ? 'block' : 'hidden' }}">
                            <x-form.select id="prerequisite_course_id" name="prerequisite_course_id" label="Pilih Kursus Prasyarat (Wajib)">
                                <option value="">-- Pilih Kursus dari Katalog --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" @selected(old('prerequisite_course_id', $project->prerequisite_course_id) == $course->id)>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </x-form.select>
                        </div>
                    </div>
                </div>

                <!-- Keputusan ACC -->
                <div>
                    <h3 class="text-sm font-bold text-slate-800 mb-3">Keputusan Persetujuan</h3>
                    <x-form.select id="status" name="status" label="Status Proyek" required>
                        <option value="Draft" @selected(old('status', $project->status) == 'Draft')>Draft (Tunda / Perlu Revisi)</option>
                        <option value="On Progress" @selected(old('status', $project->status) == 'On Progress')>Setujui (Proyek Berjalan)</option>
                    </x-form.select>
                    <p class="text-xs text-slate-500 mt-1.5">
                        <i class="fas fa-info-circle mr-1"></i> Pilih <b>Setujui</b> agar Admin Daerah bisa mulai memasukkan nama Ketua dan Anggota Tim.
                    </p>
                </div>

                <!-- Aksi -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-slate-100">
                    <x-button :href="route($routePrefix . 'index', ['status' => 'Draft'])" variant="secondary" class="flex-1">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" class="flex-1">
                        Simpan & Setujui
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleCourseSelection() {
                const isChecked = document.getElementById('is_prerequisite_active').checked;
                const courseDiv = document.getElementById('courseSelectionDiv');
                const courseSelect = document.getElementById('prerequisite_course_id');
                
                if (isChecked) {
                    courseDiv.classList.remove('hidden');
                    courseSelect.setAttribute('required', 'required');
                } else {
                    courseDiv.classList.add('hidden');
                    courseSelect.removeAttribute('required');
                }
            }
            
            document.addEventListener('DOMContentLoaded', toggleCourseSelection);
        </script>
    @endpush
</x-dashboard::layouts.dashboard>