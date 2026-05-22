<x-dashboard::layouts.dashboard title="Kategori Perpustakaan - E-Learning">
    <div class="p-2 sm:p-6">

        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[
            ['label' => 'Kategori Perpustakaan']
        ]" />

        <x-validation-errors />

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
                    <x-button type="button" x-data @click="$dispatch('open-modal', 'create-library-category')" variant="primary" icon="fas fa-plus">
                        <span class="hidden sm:inline">Tambah Kategori</span>
                        <span class="sm:hidden">Tambah</span>
                    </x-button>
                </div>
            </div>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th>No.</x-table.th>
                        <x-table.th>Nama Kategori</x-table.th>
                        <x-table.th>Deskripsi</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($libraryCategories as $key => $category)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td>
                                {{ $key + $libraryCategories->firstItem() }}
                            </x-table.td>
                            <x-table.td class="font-medium">
                                {{ $category->name }}
                            </x-table.td>
                            <x-table.td>
                                <span class="text-sm line-clamp-2">{{ $category->description ?? '-' }}</span>
                            </x-table.td>
                            <x-table.td align="center">
                                <x-table.action>
                                    <li>
                                        <button type="button"
                                            x-data
                                            @click="$dispatch('open-modal', 'edit-library-category-{{ $category->id }}')"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-amber-600 cursor-pointer text-xs font-semibold">Ubah</button>
                                    </li>
                                    <li>
                                        <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                            <x-modal-delete :id="'delete-lib-cat-' . $category->id" message="Apakah Anda yakin ingin menghapus kategori perpustakaan ini?"
                                                :item-name="$category->name" buttonText="Hapus" buttonClass="w-full text-left text-red-600 outline-none cursor-pointer text-xs font-semibold" :route="route('admin-pusat.library-categories.destroy', $category->id)" />
                                        </div>
                                    </li>
                                </x-table.action>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="4" align="center" class="py-12">
                                Tidak ada kategori perpustakaan yang ditemukan.
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table.table>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $libraryCategories->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    <x-modal name="create-library-category" title="Tambah Kategori Perpustakaan">
        <form action="{{ route('admin-pusat.library-categories.store') }}" method="POST" class="space-y-4">
            @csrf
            <x-form.input name="name" label="Nama Kategori" required placeholder="Contoh: Ebook, Peraturan, Video" value="{{ old('name') }}" />

            <x-form.textarea name="description" label="Deskripsi" rows="3" placeholder="Deskripsi singkat kategori ini..." value="{{ old('description') }}" />

            <div class="flex gap-3 pt-2">
                <x-button type="button" x-data @click="$dispatch('close-modal', 'create-library-category')" variant="white" class="flex-1">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" class="flex-1">
                    Simpan
                </x-button>
            </div>
        </form>
    </x-modal>

    @foreach($libraryCategories as $category)
        <x-modal name="edit-library-category-{{ $category->id }}" title="Edit Kategori Perpustakaan">
            <form action="{{ route('admin-pusat.library-categories.update', $category->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-form.input name="name" id="edit-name-{{ $category->id }}" label="Nama Kategori" required placeholder="Contoh: Ebook" :value="$category->name" />

                <x-form.textarea name="description" id="edit-description-{{ $category->id }}" label="Deskripsi" rows="3" placeholder="Deskripsi singkat kategori ini..." :value="$category->description" />

                <div class="flex gap-3 pt-2">
                    <x-button type="button" x-data @click="$dispatch('close-modal', 'edit-library-category-{{ $category->id }}')" variant="white" class="flex-1">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" class="flex-1">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-dashboard::layouts.dashboard>
