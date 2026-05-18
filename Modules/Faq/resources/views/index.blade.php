<x-dashboard::layouts.dashboard title="FAQ - E-Learning">
    <!-- Regions scripts removed -->
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">FAQ</span>
                    </div>
                </li>
            </ol>
        </nav>




        <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <!-- Left: Filter & Per Page -->
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <!-- Filter Level -->
                    <div class="relative w-full sm:w-48">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="level" class="pl-9 pr-3 py-2.5 w-full rounded-md border border-slate-300 text-sm
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Level</option>
                            <option value="Nasional" @selected(request('level') === 'Nasional')>Nasional</option>
                            <option value="Provinsi" @selected(request('level') === 'Provinsi')>Provinsi</option>
                            <option value="Kab/Kota" @selected(request('level') === 'Kab/Kota')>Kab/Kota</option>
                        </select>
                    </div>

                    <!-- Per Page -->
                    <div class="relative w-full sm:w-44">
                        <select name="per_page" class="px-3 py-2.5 w-full rounded-md border border-slate-300 text-sm
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach ([10, 20, 50, 100] as $page)
                                <option value="{{ $page }}" {{ request('per_page') == $page ? 'selected' : '' }}>
                                    {{ $page }} / Halaman
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Right: Search + Buttons -->
                <div class="flex w-full lg:w-96 gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari FAQ..."
                            class="pl-10 pr-4 py-2.5 w-full rounded-md border border-slate-300 text-sm
                    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Search -->
                    <button type="submit" class="inline-flex items-center gap-2 px-4 rounded-md
                bg-indigo-600 text-white text-sm font-medium
                hover:bg-indigo-700 transition">
                        <i class="fas fa-search text-xs"></i>
                        <span class="hidden sm:inline">Search</span>
                    </button>

                    <!-- Reset -->
                    <a href="{{ route($routePrefix . 'index') }}" class="inline-flex items-center gap-2 px-4 rounded-md
                border border-slate-300 text-slate-600 text-sm font-medium
                hover:bg-slate-100 transition">
                        <i class="fas fa-rotate-left text-xs"></i>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                </div>

            </div>
        </form>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div
                class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar FAQ</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total: <span class="font-medium text-slate-700" id="total-admin">{{ $faqs->total() }}</span>
                        FAQ
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    @role('admin-pusat')
                    <button x-data x-on:click="$dispatch('open-modal', 'create-faq')"
                        class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah FAQ
                    </button>
                    @endrole
                    <button
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition"
                        title="Ekspor Data">
                        <i class="fas fa-download text-xs"></i>
                        <span class="hidden sm:inline">Ekspor</span>
                    </button>
                    <button
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition"
                        title="Cetak">
                        <i class="fas fa-print text-xs"></i>
                        <span class="hidden sm:inline">Cetak</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">
                            <th class="px-4 md:px-6 py-3 text-left">No.</th>
                            <th class="px-4 md:px-6 py-3 text-left">Pertanyaan</th>
                            <th class="px-4 md:px-6 py-3 text-left">Level</th>
                            <th class="px-4 md:px-6 py-3 text-left">Dibuat Oleh</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @forelse($faqs as $key => $faq)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $key + $faqs->firstItem() }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600 font-medium">{{ Str::limit($faq->question, 60) }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    @if($faq->level === 'Nasional')
                                        <span
                                            class="px-2 py-1 bg-green-100 text-green-800 rounded border border-green-200 text-xs font-medium">Nasional</span>
                                    @elseif($faq->level === 'Provinsi')
                                        <span
                                            class="px-2 py-1 bg-blue-100 text-blue-800 rounded border border-blue-200 text-xs font-medium">Provinsi</span>
                                    @elseif($faq->level === 'Kab/Kota')
                                        <span
                                            class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded border border-yellow-200 text-xs font-medium">Kab/Kota</span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $faq->creator->name ?? 'Sistem' }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        {{-- 1. Detail --}}
                                        <li>
                                            <button x-data x-on:click="$dispatch('open-modal', 'show-faq-{{ $faq->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-blue-600">Detail</button>
                                        </li>
                                        @role('admin-pusat')
                                        {{-- 2. Ubah --}}
                                        <li>
                                            <button x-data x-on:click="$dispatch('open-modal', 'edit-faq-{{ $faq->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-amber-600">Ubah</button>
                                        </li>
                                        {{-- 3. Hapus --}}
                                        <li>
                                            <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                <x-modal-delete :id="'delete-faq-' . $faq->id" message="Apakah Anda yakin ingin menghapus FAQ ini?"
                                                    :item-name="Str::limit($faq->question, 40)" buttonText="Hapus" buttonClass="w-full text-left text-red-600 outline-none cursor-pointer" :route="route($routePrefix . 'destroy', $faq->id)" />
                                            </div>
                                        </li>
                                        @endrole
                                    </x-table.action>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-500">TIdak ada FAQ yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $faqs->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <!-- Create FAQ Modal -->
    <x-modal name="create-faq" title="Tambah FAQ Baru">
        <form action="{{ route($routePrefix . 'store') }}" method="POST"
            x-data="{ level: '{{ old('level', 'Nasional') }}' }">
            @csrf
            <div class="mb-4">
                <label for="question" class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                <input type="text" name="question" id="question" value="{{ old('question') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Level <span class="text-red-500">*</span></label>
                <select name="level" id="level" required x-model="level"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="Nasional">Nasional (Tingkat Pusat)</option>
                    <option value="Provinsi">Provinsi</option>
                    <option value="Kab/Kota">Kabupaten/Kota</option>
                </select>
            </div>

            <!-- Dynamic Dropdowns for regions removed -->

            <div class="mb-6">
                <label for="answer" class="block text-sm font-medium text-gray-700 mb-1">Jawaban <span class="text-red-500">*</span></label>
                <textarea name="answer" id="answer" rows="5" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('answer') }}</textarea>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" x-on:click="$dispatch('close-modal', 'create-faq')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Simpan
                    FAQ</button>
            </div>
        </form>
    </x-modal>

    <!-- Show FAQ Modals -->
    @foreach($faqs as $faq)
        <x-modal name="show-faq-{{ $faq->id }}" title="Detail FAQ" maxWidth="sm:max-w-2xl">
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Pertanyaan</h4>
                    <p class="text-gray-900 font-semibold">{{ $faq->question }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Level</h4>
                    @if($faq->level === 'Nasional')
                        <span
                            class="px-2 py-1 bg-green-100 text-green-800 rounded border border-green-200 text-xs font-medium">Nasional</span>
                    @elseif($faq->level === 'Provinsi')
                        <span
                            class="px-2 py-1 bg-blue-100 text-blue-800 rounded border border-blue-200 text-xs font-medium">Provinsi</span>
                    @elseif($faq->level === 'Kab/Kota')
                        <span
                            class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded border border-yellow-200 text-xs font-medium">Kab/Kota</span>
                    @endif
                </div>
                <!-- Region display removed -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Jawaban</h4>
                    <div class="prose max-w-none text-gray-800 text-sm">
                        {!! nl2br(e($faq->answer)) !!}
                    </div>
                </div>
                <div class="text-xs text-gray-400 pt-2 border-t">
                    Dibuat oleh: {{ $faq->creator->name ?? 'Sistem' }} &middot; {{ $faq->created_at->format('d M Y H:i') }}
                </div>
            </div>
        </x-modal>
    @endforeach

    <!-- Edit FAQ Modals -->
    @foreach($faqs as $faq)
        <x-modal name="edit-faq-{{ $faq->id }}" title="Edit FAQ">
            <form action="{{ route($routePrefix . 'update', $faq->id) }}" method="POST"
                x-data="{ level: '{{ old('level', $faq->level) }}' }">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="edit_question_{{ $faq->id }}"
                        class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan <span class="text-red-500">*</span></label>
                    <input type="text" name="question" id="edit_question_{{ $faq->id }}"
                        value="{{ old('question', $faq->question) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="edit_level_{{ $faq->id }}"
                        class="block text-sm font-medium text-gray-700 mb-1">Level <span class="text-red-500">*</span></label>
                    <select name="level" id="edit_level_{{ $faq->id }}" required x-model="level"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="Nasional">Nasional (Tingkat Pusat)</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Kab/Kota">Kabupaten/Kota</option>
                    </select>
                </div>

                <!-- Dynamic Dropdowns for Edit Form removed -->

                <div class="mb-6">
                    <label for="edit_answer_{{ $faq->id }}"
                        class="block text-sm font-medium text-gray-700 mb-1">Jawaban <span class="text-red-500">*</span></label>
                    <textarea name="answer" id="edit_answer_{{ $faq->id }}" rows="5" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('answer', $faq->answer) }}</textarea>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'edit-faq-{{ $faq->id }}')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Perbarui
                        FAQ</button>
                </div>
            </form>
        </x-modal>
    @endforeach

    <!-- Scripts removed -->
</x-dashboard::layouts.dashboard>