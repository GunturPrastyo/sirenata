<x-dashboard::layouts.dashboard title="Kategori Perpustakaan - E-Learning">
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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Kategori Perpustakaan</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if ($errors->any())
            <div class="mt-2 mb-4 bg-red-50 text-red-600 p-4 rounded-lg border border-red-200">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
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

                <div class="flex w-full lg:w-96 gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Cari kategori perpustakaan..." class="pl-10 pr-4 py-2.5 w-full rounded-md border border-slate-300 text-sm
                    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 px-4 rounded-md
                bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                        <i class="fas fa-search text-xs"></i>
                        <span class="hidden sm:inline">Cari</span>
                    </button>

                    <a href="{{ route('admin-pusat.library-categories.index') }}" class="inline-flex items-center gap-2 px-4 rounded-md
                border border-slate-300 text-slate-600 text-sm font-medium hover:bg-slate-100 transition">
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
                    <h2 class="text-base font-semibold text-slate-800">Daftar Kategori Perpustakaan</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total: <span class="font-medium text-slate-700">{{ $libraryCategories->total() }}</span>
                        Kategori
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" x-data @click="$dispatch('open-modal', 'create-library-category')"
                        class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Tambah Kategori Baru</span>
                        <span class="sm:hidden">Tambah</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">
                            <th class="px-4 md:px-6 py-3 text-left">No.</th>
                            <th class="px-4 md:px-6 py-3 text-left">Nama Kategori</th>
                            <th class="px-4 md:px-6 py-3 text-left">Deskripsi</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @forelse($libraryCategories as $key => $category)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600">{{ $key + $libraryCategories->firstItem() }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600 font-medium">{{ $category->name }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-500 text-sm line-clamp-2">{{ $category->description ?? '-' }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        {{-- 1. Ubah --}}
                                        <li>
                                            <button type="button"
                                                x-data
                                                @click="$dispatch('open-modal', 'edit-library-category-{{ $category->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-amber-600 cursor-pointer">Ubah</button>
                                        </li>
                                        {{-- 2. Hapus --}}
                                        <li>
                                            <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                <x-modal-delete :id="'delete-lib-cat-' . $category->id" message="Apakah Anda yakin ingin menghapus kategori perpustakaan ini?"
                                                    :item-name="$category->name" buttonText="Hapus" buttonClass="w-full text-left text-red-600 outline-none cursor-pointer" :route="route('admin-pusat.library-categories.destroy', $category->id)" />
                                            </div>
                                        </li>
                                    </x-table.action>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-500">Tidak ada kategori perpustakaan yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $libraryCategories->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    {{-- Modal: Tambah Kategori Perpustakaan --}}
    <x-modal name="create-library-category" title="Tambah Kategori Perpustakaan">
        <form action="{{ route('admin-pusat.library-categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="create-name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" id="create-name" name="name" required value="{{ old('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                    placeholder="Contoh: Ebook, Peraturan, Video">
            </div>

            <div>
                <label for="create-description" class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi
                </label>
                <textarea id="create-description" name="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                    placeholder="Deskripsi singkat kategori ini...">{{ old('description') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" x-data @click="$dispatch('close-modal', 'create-library-category')"
                    class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm text-center cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors text-sm cursor-pointer">
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>

    {{-- Modal: Edit Kategori Perpustakaan (satu modal per item) --}}
    @foreach($libraryCategories as $category)
        <x-modal name="edit-library-category-{{ $category->id }}" title="Edit Kategori Perpustakaan">
            <form action="{{ route('admin-pusat.library-categories.update', $category->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit-name-{{ $category->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit-name-{{ $category->id }}" name="name" required value="{{ old('name', $category->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                        placeholder="Contoh: Ebook">
                </div>

                <div>
                    <label for="edit-description-{{ $category->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                        Deskripsi
                    </label>
                    <textarea id="edit-description-{{ $category->id }}" name="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                        placeholder="Deskripsi singkat kategori ini...">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" x-data @click="$dispatch('close-modal', 'edit-library-category-{{ $category->id }}')"
                        class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm text-center cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-colors text-sm cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-dashboard::layouts.dashboard>
