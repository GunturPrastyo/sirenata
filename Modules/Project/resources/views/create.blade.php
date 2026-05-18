<x-dashboard::layouts.dashboard title="Tambah Proyek - E-Learning">
    @push('styles')
        @include('project::partials.create-styles')
    @endpush

    <div class="p-2 sm:p-6">
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}"
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
                        <a href="{{ route($routePrefix . 'index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Proyek</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tambah Proyek</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-8 max-w-2xl mx-auto">
            <div class="mb-4 sm:mb-6">
                <h1 class="text-lg sm:text-2xl font-bold text-gray-900">Tambah Proyek Baru</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Buat proyek baru dengan mengisi form di bawah ini</p>

                @if ($errors->any())
                    <div class="mt-4 bg-red-50 text-red-600 p-4 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <form action="{{ route($routePrefix . 'store') }}" method="POST" class="space-y-4 sm:space-y-6">
                @csrf
                <div>
                    <label for="proyekName" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Proyek <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="proyekName" name="proyekName" required value="{{ old('proyekName') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Contoh: Proyek Sektor Industri Manufaktur 2025">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="startDate" name="startDate" required value="{{ old('startDate') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="endDate" name="endDate" required value="{{ old('endDate') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                        Durasi (Bulan)
                    </label>
                    <input type="number" id="duration" name="duration" placeholder="Contoh: 12"
                        value="{{ old('duration') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="teamLeader" class="block text-sm font-medium text-gray-700 mb-2">
                        Ketua Tim <span class="text-red-500">*</span>
                    </label>
                    <select id="teamLeader" name="teamLeader" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Ketua Tim</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('teamLeader') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="teamMembers" class="block text-sm font-medium text-gray-700 mb-2">
                        Anggota Tim <span class="text-gray-500 text-xs">(Opsional, bisa pilih lebih dari satu)</span>
                    </label>
                    <select id="teamMembers" name="teamMembers[]" multiple
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, old('teamMembers') ?? []) ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Tahan tombol Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih
                        dari satu.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
                    <a href="{{ route($routePrefix . 'index') }}"
                        class="flex-1 bg-gray-200 text-gray-700 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-medium hover:bg-gray-300 transition-colors text-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                        Tambah Proyek
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @include('project::partials.create-scripts')
    @endpush
</x-dashboard::layouts.dashboard>