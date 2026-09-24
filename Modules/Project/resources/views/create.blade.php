<x-dashboard::layouts.dashboard title="Tambah Proyek - E-Learning">
    @push('styles')
        @include('project::partials.create-styles')
    @endpush

    @php
        $projectScope = request('type', str_contains($routePrefix, 'daerah') ? 'daerah' : 'pusat');
        $breadcrumbLabel = $projectScope === 'daerah' ? 'Proyek Daerah' : 'Proyek';
        $pageTitle = $projectScope === 'daerah' ? 'Tambah Proyek Daerah' : 'Tambah Proyek';
        $placeholderPrefix = $projectScope === 'daerah' ? 'RTKD' : 'RTKN';
    @endphp

    <div class="p-2 sm:p-6" x-data="projectCreateForm({{ json_encode($users ?? []) }})" x-init="watchPrerequisites()">
        <!-- Breadcrumb -->
        <x-breadcrumb :home="false" :show-home="false" :items="[
            ['label' => $breadcrumbLabel, 'url' => route($routePrefix . 'index', ['type' => $projectScope])],
            ['label' => $pageTitle],
        ]" />

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 sm:p-8 max-w-full mx-auto">
            <div class="mb-6 sm:mb-8 border-b border-slate-100 pb-5 sm:pb-6">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">{{ $pageTitle }}</h1>
                <p class="text-sm text-slate-500 mt-1">Lengkapi data proyek, filter prasyarat kursus, serta tentukan
                    Ketua dan Anggota Tim.</p>
                <x-validation-errors class="mt-4" />
            </div>

            <form action="{{ route($routePrefix . 'store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 sm:space-y-6">
                @csrf
                <x-form.input name="proyekName" label="Nama Proyek" required value="{{ old('proyekName') }}"
                    placeholder="Contoh: {{ $placeholderPrefix }} Sektor Industri Manufaktur 2025" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <x-form.input type="date" id="startDate" name="startDate" label="Tanggal Mulai" required
                        value="{{ old('startDate') }}" onchange="calculateDuration()" />
                    <x-form.input type="date" id="endDate" name="endDate" label="Tanggal Selesai" required
                        value="{{ old('endDate') }}" onchange="calculateDuration()" />
                </div>

                <input type="hidden" id="duration" name="duration" value="{{ old('duration') }}">
                <div
                    class="bg-indigo-50/50 border border-indigo-100 rounded-lg p-3 text-sm text-slate-700 font-medium flex items-center">
                    <i class="fas fa-clock text-indigo-500 mr-2 text-lg"></i>
                    Estimasi Durasi Proyek: &nbsp;<span id="durationText"
                        class="font-extrabold text-indigo-700 text-base">0 Hari</span>
                </div>

                <!-- Upload SK Proyek -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Unggah Dokumen SK (PDF)</label>
                    <input type="file" name="sk_document" accept=".pdf"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-lg p-1.5 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <p class="text-xs text-slate-500 mt-1.5">Format .pdf dengan ukuran maksimal 5 MB.</p>
                </div>

                <!-- ============================================== -->
                <!-- PENGATURAN TIM PROYEK                          -->
                <!-- ============================================== -->
                @if (!str_contains($routePrefix, 'admin-kab-kota') && !str_contains($routePrefix, 'admin-province'))
                    <div class="border-t border-slate-200 pt-6 mt-6 space-y-6">
                        <h3 class="text-base font-bold text-slate-800">Pengaturan Tim Proyek</h3>

                        <!-- FILTER PRASYARAT KURSUS -->
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 sm:p-5">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-sm font-bold text-slate-800">
                                    <i class="fas fa-filter text-indigo-600 mr-1.5"></i> Filter Prasyarat Kursus
                                </label>
                                <span class="text-xs text-slate-500 font-medium">
                                    Filter Aktif: <strong class="text-indigo-600"
                                        x-text="selectedCourses.length + ' Kursus'"></strong>
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mb-3">
                                Hanya pengguna yang <b>lulus 100%</b> pada seluruh kursus yang dipilih di bawah ini yang
                                akan tampil pada opsi Ketua & Anggota Tim.
                            </p>

                            <!-- Searchbox Prasyarat (Debounce 300ms) -->
                            <div class="mb-2" x-show="allCourses.length > 5">
                                <input type="text" x-model.debounce.300ms="searchCourse"
                                    placeholder="Cari prasyarat kursus..."
                                    class="w-full text-xs rounded-md border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 p-2">
                            </div>

                            <!-- Checkbox List Kursus Prasyarat -->
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 max-h-48 overflow-y-auto p-2 bg-white rounded-lg border border-slate-200">
                                <template x-for="course in filteredCourses" :key="course.id">
                                    <label
                                        class="flex items-center gap-2.5 p-2 rounded-md hover:bg-slate-50 cursor-pointer border border-transparent hover:border-slate-200 transition text-xs font-medium text-slate-700">
                                        <input type="checkbox" name="prerequisite_course_ids[]"
                                            :value="String(course.id)" x-model="selectedCourses"
                                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                                        <span class="line-clamp-1" x-text="course.name"></span>
                                    </label>
                                </template>
                                <div x-show="filteredCourses.length === 0"
                                    class="col-span-full text-xs text-slate-400 text-center py-2">
                                    Kursus tidak ditemukan.
                                </div>
                            </div>
                        </div>

                        <!-- PEMILIHAN KETUA & ANGGOTA TIM -->
                        <div class="space-y-5">
                            <!-- Select Ketua Tim -->
                            <div>
                                <label for="teamLeader" class="block text-sm font-medium text-slate-700 mb-1">
                                    Ketua Tim <span class="text-red-500">*</span>
                                </label>
                                <select id="teamLeader" name="teamLeader" required
                                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5">
                                    <option value="">-- Pilih Ketua Tim --</option>
                                    <template x-for="user in filteredUsers" :key="user.id">
                                        <option :value="user.id" x-text="user.name"></option>
                                    </template>
                                </select>
                                <p x-show="filteredUsers.length === 0" class="text-xs text-amber-600 mt-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Tidak ada pengguna yang memenuhi
                                    seluruh prasyarat kursus yang dipilih.
                                </p>
                            </div>

                            <!-- Dropdown Multi-Select Anggota Tim -->
                            <div class="relative" @click.outside="isMemberDropdownOpen = false">
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-sm font-medium text-slate-700">Anggota Tim
                                        (Opsional)</label>
                                    <span class="text-sm text-slate-500"
                                        x-text="selectedMembers.length + ' Anggota Terpilih'"></span>
                                </div>

                                <!-- Badges Anggota Terpilih (Diperbesar) -->
                                <div class="flex flex-wrap gap-2 my-2.5" x-show="selectedMembers.length > 0">
                                    <template x-for="user in selectedMemberObjects" :key="user.id">
                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <span x-text="user.name"></span>
                                            <button type="button" @click="removeMember(user.id)"
                                                class="text-indigo-400 hover:text-indigo-700 focus:outline-none">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        </span>
                                    </template>
                                </div>

                                <!-- Dropdown Trigger Button -->
                                <button type="button" @click="isMemberDropdownOpen = !isMemberDropdownOpen"
                                    class="w-full flex items-center justify-between bg-white border border-slate-300 rounded-lg p-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <span
                                        x-text="selectedMembers.length > 0 ? selectedMembers.length + ' Anggota Terpilih' : '-- Pilih Anggota Tim --'"
                                        :class="selectedMembers.length > 0 ? 'text-slate-900 font-medium' : 'text-slate-400'"></span>
                                    <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200"
                                        :class="{ 'rotate-180': isMemberDropdownOpen }"></i>
                                </button>

                                <!-- Dropdown Menu Box -->
                                <div x-show="isMemberDropdownOpen"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-20 left-0 right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-lg p-3.5 space-y-3">

                                    <!-- Searchbar di Dalam Dropdown -->
                                    <div class="relative">
                                        <input type="text" x-model.debounce.300ms="searchMember"
                                            placeholder="Cari nama anggota tim..."
                                            class="w-full text-sm rounded-md border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 p-2.5 pr-9">
                                        <i class="fas fa-search absolute right-3 top-3 text-slate-400 text-sm"></i>
                                    </div>

                                    <!-- List Checkbox Anggota Tim -->
                                    <div class="max-h-56 overflow-y-auto space-y-1 p-1">
                                        <template x-for="user in filteredMembersList" :key="user.id">
                                            <label
                                                class="flex items-center gap-3 p-2.5 rounded-md hover:bg-slate-50 cursor-pointer text-sm font-medium text-slate-700 transition">
                                                <input type="checkbox" name="teamMembers[]" :value="String(user.id)"
                                                    x-model="selectedMembers"
                                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4.5 h-4.5 cursor-pointer">
                                                <span class="line-clamp-1" x-text="user.name"></span>
                                            </label>
                                        </template>
                                        <div x-show="filteredMembersList.length === 0"
                                            class="text-sm text-slate-500 text-center py-4">
                                            Tidak ada anggota yang cocok.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-slate-100">
                    <x-button :href="route($routePrefix . 'index', ['type' => $projectScope])" variant="secondary" class="flex-1">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" class="flex-1">
                        Simpan & Aktifkan Proyek
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @include('project::partials.create-scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('projectCreateForm', (allUsers) => ({
                    selectedCourses: [],
                    selectedMembers: [],
                    searchCourse: '',
                    searchMember: '',
                    isMemberDropdownOpen: false,
                    allUsers: allUsers,
                    allCourses: @json($courses ?? []),

                    get filteredCourses() {
                        if (!this.searchCourse.trim()) return this.allCourses;
                        return this.allCourses.filter(c =>
                            c.name.toLowerCase().includes(this.searchCourse.toLowerCase())
                        );
                    },

                    get filteredUsers() {
                        if (this.selectedCourses.length === 0) {
                            return this.allUsers;
                        }

                        return this.allUsers.filter(user => {
                            const userCourses = (user.completed_courses || []).map(id => String(
                                id));
                            return this.selectedCourses.every(courseId =>
                                userCourses.includes(String(courseId))
                            );
                        });
                    },

                    get filteredMembersList() {
                        if (!this.searchMember.trim()) return this.filteredUsers;
                        return this.filteredUsers.filter(u =>
                            u.name.toLowerCase().includes(this.searchMember.toLowerCase())
                        );
                    },

                    get selectedMemberObjects() {
                        return this.allUsers.filter(u => this.selectedMembers.includes(String(u.id)));
                    },

                    removeMember(userId) {
                        this.selectedMembers = this.selectedMembers.filter(id => String(id) !== String(
                            userId));
                    },

                    watchPrerequisites() {
                        this.$watch('selectedCourses', () => {
                            const validIds = new Set(this.filteredUsers.map(u => String(u.id)));
                            this.selectedMembers = this.selectedMembers.filter(id => validIds.has(
                                String(id)));

                            const teamLeaderSelect = document.getElementById('teamLeader');
                            if (teamLeaderSelect && !validIds.has(String(teamLeaderSelect.value))) {
                                teamLeaderSelect.value = '';
                            }
                        });
                    }
                }));
            });

            function calculateDuration() {
                const start = document.getElementById('startDate').value;
                const end = document.getElementById('endDate').value;
                const durationInput = document.getElementById('duration');
                const durationText = document.getElementById('durationText');

                if (start && end) {
                    const startDate = new Date(start);
                    const endDate = new Date(end);
                    const diffTime = endDate - startDate;

                    let days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    days = days < 0 ? 0 : days;

                    durationInput.value = days;
                    durationText.innerText = days + ' Hari';
                } else {
                    durationInput.value = '';
                    durationText.innerText = '0 Hari';
                }
            }
            document.addEventListener('DOMContentLoaded', calculateDuration);
        </script>
    @endpush
</x-dashboard::layouts.dashboard>
