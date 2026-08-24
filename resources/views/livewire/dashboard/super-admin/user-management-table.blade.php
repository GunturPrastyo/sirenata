<div x-data="{
    selected: [],
    toggleAll(ids) {
        // Alpine.js akan otomatis mencentang/menghapus centang semua x-model
        this.selected.length === ids.length ?
            this.selected = [] :
            this.selected = ids.map(String) // Konversi ke string agar cocok dengan value checkbox
    }
}" 
x-on:bulk-cleared.window="selected = []" 
x-on:submit.prevent="">

    <x-dashboard::filter-card
        title="Daftar User"
        :total="$users->total() . ' user'"
        :resetUrl="route('super-admin.user-management.index')">

        <x-slot name="actions">
            <x-button href="{{ route('super-admin.user-management.create') }}" icon="fas fa-plus" size="sm">
                Tambah User
            </x-button>
        </x-slot>

        <x-slot name="filter_inputs">
            <!-- Limit -->
            <div class="w-full sm:w-44">
                <label class="block text-xs font-medium text-slate-500 mb-1">
                    Data per Halaman
                </label>
                <select wire:model.live="limit"
                    class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="10">10 / per Halaman</option>
                    <option value="20">20 / per Halaman</option>
                    <option value="50">50 / per Halaman</option>
                    <option value="100">100 / per Halaman</option>
                </select>
            </div>

            <!-- Search -->
            <div class="flex-1 min-w-[240px] w-full">
                <label class="block text-xs font-medium text-slate-500 mb-1">
                    Pencarian
                </label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, email..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </x-slot>

        <!-- Bulk Actions -->
        <div x-show="selected.length > 0" x-transition class="p-5 border-b border-slate-200 bg-slate-50/50" id="bulk-actions" style="display: none;">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-sm text-slate-600">
                    <span class="font-semibold text-slate-900" x-text="selected.length"></span> admin dipilih ·
                    <button type="button" class="text-indigo-600 hover:underline" @click="toggleAll(@js($users->pluck('id')))">Pilih semua</button>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="$dispatch('open-modal', 'bulk-activate-confirmation')" class="px-3 py-1.5 rounded-md text-sm bg-emerald-50 text-emerald-700 hover:bg-emerald-100 cursor-pointer">
                        <i class="fas fa-user-check mr-1"></i> Aktifkan
                    </button>
                    <button type="button" @click="$dispatch('open-modal', 'bulk-deactivate-confirmation')" class="px-3 py-1.5 rounded-md text-sm bg-amber-50 text-amber-700 hover:bg-amber-100 cursor-pointer">
                        <i class="fas fa-user-slash mr-1"></i> Nonaktifkan
                    </button>
                    <button type="button" @click="$dispatch('open-modal', 'bulk-delete-confirmation')"
                        class="px-3 py-1.5 rounded-md text-sm bg-red-50 text-red-700 hover:bg-red-100 cursor-pointer">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Aktifkan -->
        <x-modal name="bulk-activate-confirmation" title="Konfirmasi Aktifkan User" maxWidth="sm:max-w-md">
            <div class="p-6 text-center">
                <div class="flex justify-center mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100">
                        <svg class="w-6 h-6 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Aktifkan User</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Anda yakin ingin mengaktifkan <strong x-text="selected.length"></strong> user yang dipilih?
                </p>
                <div class="flex justify-center gap-3">
                    <!-- Event close-modal akan dijalankan, backend Livewire Anda akan memanggil bulk-cleared -->
                    <button type="button" @click="$wire.bulkActivate(selected); $dispatch('close-modal', 'bulk-activate-confirmation')"
                        class="px-4 py-2 cursor-pointer text-sm font-medium text-white bg-emerald-600 rounded-md hover:bg-emerald-700">
                        Ya, Aktifkan
                    </button>
                    <button type="button" @click="$dispatch('close-modal', 'bulk-activate-confirmation')"
                        class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Batal
                    </button>
                </div>
            </div>
        </x-modal>

        <!-- Modal Konfirmasi Nonaktifkan -->
        <x-modal name="bulk-deactivate-confirmation" title="Konfirmasi Nonaktifkan User" maxWidth="sm:max-w-md">
            <div class="p-6 text-center">
                <div class="flex justify-center mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-amber-100">
                        <svg class="w-6 h-6 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Nonaktifkan User</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Anda yakin ingin menonaktifkan <strong x-text="selected.length"></strong> user yang dipilih?
                </p>
                <div class="flex justify-center gap-3">
                    <button type="button" @click="$wire.bulkDeactivate(selected); $dispatch('close-modal', 'bulk-deactivate-confirmation')"
                        class="px-4 py-2 cursor-pointer text-sm font-medium text-white bg-amber-600 rounded-md hover:bg-amber-700">
                        Ya, Nonaktifkan
                    </button>
                    <button type="button" @click="$dispatch('close-modal', 'bulk-deactivate-confirmation')"
                        class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Batal
                    </button>
                </div>
            </div>
        </x-modal>

        <!-- Modal Konfirmasi Hapus -->
        <x-modal name="bulk-delete-confirmation" title="Konfirmasi Hapus User" maxWidth="sm:max-w-md">
            <div class="p-6 text-center">
                <div class="flex justify-center mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                        <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Anda yakin?</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Anda akan menghapus <strong x-text="selected.length"></strong> user secara permanen. Aksi ini tidak dapat dibatalkan.
                </p>
                <div class="flex justify-center gap-3">
                    <button type="button" @click="$wire.bulkDelete(selected); $dispatch('close-modal', 'bulk-delete-confirmation')"
                        class="px-4 py-2 cursor-pointer text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Ya, Hapus
                    </button>
                    <button type="button" @click="$dispatch('close-modal', 'bulk-delete-confirmation')"
                        class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Batal
                    </button>
                </div>
            </div>
        </x-modal>

        <x-table.table plain>
            <thead>
                <tr>
                    <x-table.th class="w-10">
                        {{-- Fitur master checkbox jika diperlukan:
                        <input type="checkbox" @change="toggleAll(@js($users->pluck('id')))"
                            :checked="selected.length === {{ $users->count() }} && {{ $users->count() }} > 0"
                            class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> 
                        --}}
                    </x-table.th>
                    <x-table.th>Name</x-table.th>
                    <x-table.th>Email</x-table.th>
                    <x-table.th>Role</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th>Provinsi</x-table.th>
                    <x-table.th>Kabupaten/Kota</x-table.th>
                    <x-table.th align="center">Aksi</x-table.th>
                </tr>
            </thead>

            <tbody id="admin-table-body" class="divide-y divide-slate-200">
                @forelse ($users as $user)
                    <!-- PERUBAHAN: Tambahkan wire:key di sini untuk mencegah state DOM tertukar -->
                    <tr wire:key="user-{{ $user->id }}" class="hover:bg-slate-50 transition">
                        <x-table.td>
                            <!-- PERUBAHAN: Gunakan x-model="selected" agar terikat langsung dengan array -->
                            <input type="checkbox" 
                                x-model="selected" 
                                value="{{ $user->id }}"
                                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        </x-table.td>
                        <x-table.td>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-semibold">
                                    {{ $user->name[0] }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                </div>
                            </div>
                        </x-table.td>

                        <x-table.td>
                            <p class="text-slate-600">{{ $user->email }}</p>
                        </x-table.td>

                        <x-table.td>
                            <p class="text-slate-600">{{ $user->getRoleNames()->first() }}</p>
                        </x-table.td>
                        <x-table.td class="lg:table-cell">
                            @if ($user->is_active)
                                <x-badge color="emerald" text="Aktif" />
                            @else
                                <x-badge color="amber" text="Nonaktif" />
                            @endif
                        </x-table.td>
                        <x-table.td class="lg:table-cell">
                            <p class="text-slate-600">{{ $user->scopeArea?->province?->name }}</p>
                        </x-table.td>
                        <x-table.td class="lg:table-cell">
                            <p class="text-slate-600">{{ $user->scopeArea?->regency?->name }}</p>
                        </x-table.td>

                        <x-table.td align="center">
                            <x-table.action>
                                <li>
                                    <a href="{{ route('super-admin.user-management.edit', $user->id) }}"
                                        class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-left">Edit</a>
                                </li>
                                <li>
                                    <a href="{{ route('super-admin.user-management.show', $user->id) }}"
                                        class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-left">Show</a>
                                </li>
                                <li>
                                    <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                        <x-modal-delete :id="$user->id" message="Are you sure delete user"
                                            :item-name="$user->name" :route="route('super-admin.user-management.destroy', $user->id)" />
                                    </div>
                                </li>
                            </x-table.action>
                        </x-table.td>
                    </tr>
                @empty
                    <tr>
                        <x-table.td colspan="8" align="center" class="py-12">
                            <p class="text-sm text-slate-500">Tidak ada data admin</p>
                        </x-table.td>
                    </tr>
                @endforelse
            </tbody>
        </x-table.table>

        <div class="px-5 py-4 border-t border-slate-200">
            {{ $users->links() }}
        </div>
    </x-dashboard::filter-card>
</div>