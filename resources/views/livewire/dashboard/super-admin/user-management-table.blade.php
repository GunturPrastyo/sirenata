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
}" x-on:bulk-cleared.window="selected = []">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <div class="relative">
                    <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <select
                        class="pl-9  py-2.5 w-full rounded-md border border-slate-300 text-sm
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="relative">
                    <select wire:model.lazy="limit"
                        class="py-2.5 w-full rounded-md border border-slate-300 text-sm
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="10">10 / per Halaman</option>
                        <option value="20">20 / per Halaman</option>
                        <option value="50">50 / per Halaman</option>
                        <option value="100">100 / per Halaman</option>
                    </select>
                </div>

                <button wire:click='resetFilter'
                    class="flex items-center gap-2 px-4 py-2.5 text-sm rounded-md
                        bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                    <i class="fas fa-rotate-left text-xs"></i>
                    Reset
                </button>
            </div>
            <div class="relative w-full md:w-72">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" wire:model.lazy="search" placeholder="Cari nama, email, atau telepon..."
                    class="pl-10 pr-4 py-2.5 w-full rounded-md border border-slate-300 text-sm
                        focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <div x-show="selected.length > 0" x-transition class=" mt-5 pt-4 border-t border-slate-200" id="bulk-actions">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-sm text-slate-600">
                    <span class="font-semibold text-slate-900" x-text="selected.length"></span> admin dipilih ·
                    <button class="text-indigo-600 hover:underline">Pilih semua</button>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button class="px-3 py-1.5 rounded-md text-sm bg-emerald-50 text-emerald-700 hover:bg-emerald-100">
                        <i class="fas fa-user-check mr-1"></i> Aktifkan
                    </button>
                    <button class="px-3 py-1.5 rounded-md text-sm bg-amber-50 text-amber-700 hover:bg-amber-100">
                        <i class="fas fa-user-slash mr-1"></i> Nonaktifkan
                    </button>
                    <button @click="$wire.bulkDelete(selected)"
                        class="px-3 py-1.5 rounded-md text-sm bg-red-50 text-red-700 hover:bg-red-100">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div
            class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Daftar Admin Pusat</h2>
                <p class="text-sm text-slate-500 mt-1">
                    Total: <span class="font-medium text-slate-700" id="total-admin">{{ $users->total() }}</span> admin
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-lg
                    text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition"
                    title="Ekspor Data">
                    <i class="fas fa-download text-xs"></i>
                    <span class="hidden sm:inline">Ekspor</span>
                </button>

                <button
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-lg
                    text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition"
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
                        <th class="px-4 md:px-6 py-3 w-10">
                            {{-- <input type="checkbox" @click="toggleAll(@js($users->pluck('id')))"
                                :checked="selected.length === {{ $users->count() }}"
                                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"> --}}
                        </th>
                        <th class="px-4 md:px-6 py-3 text-left">Name</th>
                        <th class="px-4 md:px-6 py-3 text-left">Email</th>
                        <th class="px-4 md:px-6 py-3 text-left">Role</th>
                        <th class="px-4 md:px-6 py-3 text-left">Status</th>
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


                            <td class="px-4 md:px-6 py-3 text-center">
                                <x-table.action>
                                    <li>
                                        <a href="{{ route('super-admin.user-management.edit', $user->id) }}"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Edit</a>
                                    </li>
                                </x-table.action>
                            </td>
                        </tr>
                    @empty
                        <tr class="">
                            <td colspan="6" class="px-6 py-12 text-center">
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
    </div>
</div>
