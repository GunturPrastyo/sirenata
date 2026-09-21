<x-dashboard::layouts.dashboard title="Edit Proyek - E-Learning">
    @push('styles')
        @include('project::partials.create-styles')
    @endpush

    @php
        $placeholderPrefix = str_contains($routePrefix, 'pusat') ? 'RTKN' : 'RTKD';

        // Fetch data kursus prasyarat
        $prerequisiteIds = $project->prerequisiteCourseIds();
        $courseModelClass = match (true) {
            class_exists('Modules\LMS\Models\Course') => 'Modules\LMS\Models\Course',
            class_exists('Modules\Course\Models\Course') => 'Modules\Course\Models\Course',
            class_exists('Modules\Lms\Models\Course') => 'Modules\Lms\Models\Course',
            class_exists('App\Models\Course') => 'App\Models\Course',
            default => null,
        };
        $prerequisiteCourses =
            $courseModelClass && !empty($prerequisiteIds)
                ? $courseModelClass::whereIn('id', $prerequisiteIds)->get()
                : collect();
    @endphp

    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Proyek', 'url' => route($routePrefix . 'index')], ['label' => 'Edit Proyek']]" />

        <div class="bg-white rounded-lg border border-slate-100 shadow-sm p-6 sm:p-8 max-w-full mx-auto">
            <div class="mb-6 sm:mb-8 border-b border-slate-100 pb-5 sm:pb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Edit Proyek</h1>
                    <p class="text-sm text-slate-500 mt-1">Perbarui data proyek dan Dokumen SK.</p>
                </div>
                @php
                    $statusColor = match ($project->status) {
                        'On Progress' => 'amber-solid',
                        'Completed' => 'green-solid',
                        default => 'slate-solid',
                    };
                @endphp
                <x-badge :color="$statusColor" :text="$project->status === 'Completed' ? 'Selesai' : ($project->status ?? 'Draft')" />
            </div>

            <x-validation-errors class="mb-6" />

            <form action="{{ route($routePrefix . 'update', $project->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 sm:space-y-6">
                @csrf
                @method('PUT')
                <x-form.input name="proyekName" label="Nama Proyek" required
                    value="{{ old('proyekName', $project->name) }}"
                    placeholder="Contoh: {{ $placeholderPrefix }} Sektor Industri Manufaktur 2025" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <x-form.input type="date" id="startDate" name="startDate" label="Tanggal Mulai" required
                        value="{{ old('startDate', $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}"
                        onchange="calculateDuration()" />
                    <x-form.input type="date" id="endDate" name="endDate" label="Tanggal Selesai" required
                        value="{{ old('endDate', $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '') }}"
                        onchange="calculateDuration()" />
                </div>

                <input type="hidden" id="duration" name="duration" value="{{ old('duration', $project->duration) }}">
                <div
                    class="bg-indigo-50/50 border border-indigo-100 rounded-lg p-3 text-sm text-slate-700 font-medium flex items-center">
                    <i class="fas fa-clock text-indigo-500 mr-2 text-lg"></i>
                    Estimasi Durasi Proyek: &nbsp;<span id="durationText"
                        class="font-extrabold text-indigo-700 text-base">0 Hari</span>
                </div>

                <!-- Input Ubah Dokumen SK -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Dokumen SK Proyek</label>
                    @if ($project->sk_document)
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $project->sk_document) }}" target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 rounded-md text-xs font-bold hover:bg-slate-200 transition border border-slate-200">
                                <i class="fas fa-file-pdf text-red-500"></i> Lihat SK Saat Ini
                            </a>
                        </div>
                    @endif
                    <input type="file" name="sk_document" accept=".pdf"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-lg p-1.5 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <p class="text-xs text-slate-500 mt-1.5">Abaikan jika Anda tidak ingin mengubah/mengganti dokumen SK
                        yang ada.</p>
                </div>

                <!-- PENGATURAN TIM PROYEK -->
                @if ($project->status === 'On Progress' || $project->status === 'Completed')
                    <div class="border-t border-slate-200 pt-6 mt-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Pengaturan Tim Proyek</h3>

                        @if ($project->is_prerequisite_active)
                            <div
                                class="bg-indigo-50 border border-indigo-100 text-indigo-800 p-4 rounded-lg text-sm mb-5 space-y-3">
                                <div class="flex items-start gap-2.5">
                                    <i class="fas fa-graduation-cap text-indigo-600 mt-0.5 text-base"></i>
                                    <div>
                                        <span class="font-bold">Pusat mewajibkan prasyarat kursus.</span>
                                        <p class="text-xs text-indigo-600 mt-0.5">Hanya pengguna yang lulus kursus di
                                            bawah ini yang muncul dalam daftar pilihan tim.</p>
                                    </div>
                                </div>

                                @if ($prerequisiteCourses->count() > 0)
                                    <div class="pt-2 border-t border-indigo-100">
                                        <span
                                            class="block text-xs font-semibold text-indigo-900 uppercase tracking-wider mb-2">Daftar
                                            Kursus Prasyarat:</span>
                                        <div class="flex flex-wrap gap-2 max-h-36 overflow-y-auto pr-1">
                                            @foreach ($prerequisiteCourses as $course)
                                                <span
                                                    class="inline-block text-xs font-medium text-indigo-800 bg-white border border-indigo-200 px-3 py-1 rounded-full shadow-sm">
                                                    {{ $course->name ?? ($course->title ?? 'Kursus #' . $course->id) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="space-y-6">
                            <x-form.select id="teamLeader" name="teamLeader" label="Ketua Tim" required>
                                <option value="">Pilih Ketua Tim</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected((old('teamLeader') ?? $project->team_leader) == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-form.select>

                            <div>
                                <!-- Hasil Sementara (Di atas label) -->
                                <div id="selectedMembersContainer" class="mb-3 hidden">
                                    <span
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Hasil
                                        Sementara (Anggota Terpilih):</span>
                                    <div id="selectedMembersBadges"
                                        class="flex flex-wrap gap-2 p-2.5 bg-slate-50 border border-slate-200 rounded-lg min-h-[42px]">
                                    </div>
                                </div>

                                <label class="block text-sm font-medium text-slate-700 mb-2">Anggota Tim
                                    (Opsional)</label>

                                <!-- Box Checklist Anggota Tim -->
                                <div
                                    class="border border-slate-300 rounded-lg p-3 max-h-56 overflow-y-auto space-y-1.5 bg-white">
                                    @php
                                        $selectedMembers =
                                            old('teamMembers') ??
                                            (is_array($project->team_members)
                                                ? $project->team_members
                                                : json_decode($project->team_members ?? '[]', true) ?? []);
                                    @endphp
                                    @foreach ($users as $user)
                                        <label
                                            class="flex items-center gap-3 p-2 rounded-md hover:bg-slate-50 cursor-pointer transition border border-transparent hover:border-slate-200">
                                            <input type="checkbox" name="teamMembers[]" value="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                class="team-member-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4"
                                                @checked(in_array($user->id, $selectedMembers))>
                                            <span class="text-sm font-medium text-slate-700">{{ $user->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
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
                                Form pemilihan Ketua dan Anggota Tim dikunci dan akan muncul otomatis pada halaman ini
                                setelah Admin Pusat menyetujui dan mengaktifkan proyek ini.
                            </div>
                        </div>
                    </div>
                @endif

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
                    const diffTime = endDate - startDate;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    const days = diffDays < 0 ? 0 : diffDays;

                    durationInput.value = days;
                    durationText.innerText = days + ' Hari';
                } else {
                    durationInput.value = '0';
                    durationText.innerText = '0 Hari';
                }
            }

            function updateSelectedMembersBadges() {
                const checkboxes = document.querySelectorAll('.team-member-checkbox:checked');
                const container = document.getElementById('selectedMembersContainer');
                const badgesWrapper = document.getElementById('selectedMembersBadges');

                if (!container || !badgesWrapper) return;

                badgesWrapper.innerHTML = '';

                if (checkboxes.length > 0) {
                    container.classList.remove('hidden');
                    checkboxes.forEach(cb => {
                        const name = cb.getAttribute('data-name');
                        const val = cb.value;
                        const badge = document.createElement('span');
                        badge.className =
                            'inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-semibold rounded-full';
                        badge.innerHTML = `
                            ${name}
                            <button type="button" onclick="uncheckMember('${val}')" class="hover:text-indigo-900 focus:outline-none">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                        `;
                        badgesWrapper.appendChild(badge);
                    });
                } else {
                    container.classList.add('hidden');
                }
            }

            function uncheckMember(id) {
                const cb = document.querySelector(`.team-member-checkbox[value="${id}"]`);
                if (cb) {
                    cb.checked = false;
                    updateSelectedMembersBadges();
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                calculateDuration();
                updateSelectedMembersBadges();

                document.querySelectorAll('.team-member-checkbox').forEach(cb => {
                    cb.addEventListener('change', updateSelectedMembersBadges);
                });
            });
        </script>
    @endpush
</x-dashboard::layouts.dashboard>
