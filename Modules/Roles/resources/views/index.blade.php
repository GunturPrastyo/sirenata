<x-dashboard::layouts.dashboard title="Daftar Role">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('super-admin.dashboard') }}"
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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Daftar Laporan RTKN</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <a href="{{ route('super-admin.roles.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                Tambah Role
            </a>
        </div>

        <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-44">
                        <select name="per_page"
                            class="px-3 py-2.5 w-full rounded-md border border-slate-300 text-sm
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach ([10, 20, 50, 100] as $page)
                                <option value="{{ $page }}"
                                    {{ request('per_page') == $page ? 'selected' : '' }}>
                                    {{ $page }} / Halaman
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex w-full lg:w-96 gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="pl-10 pr-4 py-2.5 w-full rounded-md border border-slate-300 text-sm
                    focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Search -->
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 rounded-md
                bg-indigo-600 text-white text-sm font-medium
                hover:bg-indigo-700 transition">
                        <i class="fas fa-search text-xs"></i>
                        <span class="hidden sm:inline">Search</span>
                    </button>

                    <!-- Reset -->
                    <a href="{{ route('super-admin.roles.index') }}"
                        class="inline-flex items-center gap-2 px-4 rounded-md
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
                    <h2 class="text-base font-semibold text-slate-800">Daftar Role</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total: <span class="font-medium text-slate-700" id="total-admin">{{ $roles->total() }}</span>
                        Role
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md
                    text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition"
                        title="Ekspor Data">
                        <i class="fas fa-download text-xs"></i>
                        <span class="hidden sm:inline">Ekspor</span>
                    </button>

                    <button
                        class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md
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

                            <th class="px-4 md:px-6 py-3 text-left">No.</th>
                            <th class="px-4 md:px-6 py-3 text-left">Nama Role</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="admin-table-body" class="divide-y divide-slate-200">
                        @forelse ($roles as $key => $role)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3 ">
                                    <p class="text-slate-600">{{ $key + $roles->firstItem() }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 ">
                                    <p class="text-slate-600">{{ $role->name }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        <li>
                                            <a href="{{ route('super-admin.roles.edit', $role->uuid) }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Edit</a>
                                        </li>
                                        <li>
                                            <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                <x-modal-delete :id="$role->uuid" message="Are you sure delete Role"
                                                    :item-name="$role->name" :route="route('super-admin.roles.destroy', $role->uuid)" />
                                            </div>
                                        </li>
                                    </x-table.action>
                                </td>
                            </tr>
                        @empty
                            <tr class="">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-500">Tidak ada data Role</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $roles->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>
