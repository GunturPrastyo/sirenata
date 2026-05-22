<div x-data="{
    selected: [],
    toggle(id) {
        this.selected.includes(id) ?
            this.selected = this.selected.filter(i => i !== id) :
            this.selected.push(id)
    },
    toggleAll(ids) {
        this.selected.length === ids.length ?
            this.selected = [] :
            this.selected = ids
    }
}" x-on:bulk-cleared.window="selected = []" x-on:submit.prevent="">

    <x-dashboard::filter-card
        title="Daftar User"
        :total="$users->total() . ' user'"
        :resetUrl="route('super-admin.user-management.index')">

        <x-slot name="actions">
            <a href="{{ route('super-admin.user-management.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors cursor-pointer">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </a>
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
                    <button class="text-indigo-600 hover:underline" @click="toggleAll(@js($users->pluck('id')))">Pilih semua</button>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button class="px-3 py-1.5 rounded-md text-sm bg-emerald-50 text-emerald-700 hover:bg-emerald-100 cursor-pointer">
                        <i class="fas fa-user-check mr-1"></i> Aktifkan
                    </button>
                    <button class="px-3 py-1.5 rounded-md text-sm bg-amber-50 text-amber-700 hover:bg-amber-100 cursor-pointer">
                        <i class="fas fa-user-slash mr-1"></i> Nonaktifkan
                    </button>
                    <button @click="$wire.bulkDelete(selected)"
                        class="px-3 py-1.5 rounded-md text-sm bg-red-50 text-red-700 hover:bg-red-100 cursor-pointer">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 border-b border-slate-200">
                    <tr class="text-slate-500 uppercase text-xs">
                        <th class="px-4 md:px-6 py-3 w-10">
                            {{-- <input type="checkbox" @click="toggleAll(@js($users->pluck('id')))"
                                :checked="selected.length === {{ $users->count() }}"
                                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> --}}
                        </th>
                        <th class="px-4 md:px-6 py-3 text-left">Name</th>
                        <th class="px-4 md:px-6 py-3 text-left">Email</th>
                        <th class="px-4 md:px-6 py-3 text-left">Role</th>
                        <th class="px-4 md:px-6 py-3 text-left">Status</th>
                        <th class="px-4 md:px-6 py-3 text-left">Provinsi</th>
                        <th class="px-4 md:px-6 py-3 text-left">Kabupaten/Kota</th>
                        <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody id="admin-table-body" class="divide-y divide-slate-200">

                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 md:px-6 py-3">
                                <input type="checkbox" @change="toggle(@js($user->id))"
                                    :checked="selected.includes({{ $user->id }})"
                                    class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-4 md:px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-semibold">
                                        {{ $user->name[0] }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 md:px-6 py-3 ">
                                <p class="text-slate-600">{{ $user->email }}</p>
                            </td>

                            <td class="px-4 md:px-6 py-3">
                                <p class="text-slate-600">{{ $user->getRoleNames()->first() }}</p>
                            </td>
                            <td class="px-4 md:px-6 py-3  lg:table-cell">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    bg-emerald-50 text-emerald-700">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-3  lg:table-cell">
                                <p class="text-slate-600">{{ $user->scopeArea?->province?->name }}</p>
                            </td>
                            <td class="px-4 md:px-6 py-3  lg:table-cell">
                                <p class="text-slate-600">{{ $user->scopeArea?->regency?->name }}</p>
                            </td>

                            <td class="px-4 md:px-6 py-3 text-center">
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
                            </td>
                        </tr>
                    @empty
                        <tr class="">
                            <td colspan="8" class="px-6 py-12 text-center">
                                <p class="text-sm text-slate-500">Tidak ada data admin</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-200">
            {{ $users->links() }}
        </div>
    </x-dashboard::filter-card>
</div>
