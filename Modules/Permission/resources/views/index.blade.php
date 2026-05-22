<x-dashboard::layouts.dashboard title="Daftar Permission">
    <div class="p-2 sm:p-6" x-data="{ 
        openCreateModal: {{ $errors->any() && !old('edit_id') ? 'true' : 'false' }}, 
        openEditModal: {{ $errors->any() && old('edit_id') ? 'true' : 'false' }},
        editPermissionId: '{{ old('edit_id') }}',
        editPermissionName: '{{ old('name') }}',
        editPermissionAction: '{{ old('edit_id') ? route('super-admin.permissions.update', old('edit_id')) : '' }}',
        openEdit(id, name, route) {
            this.editPermissionId = id;
            this.editPermissionName = name;
            this.editPermissionAction = route;
            this.openEditModal = true;
        }
    }">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :items="[['label' => 'Daftar Permission']]" />

        <x-dashboard::filter-card
            title="Daftar Permission"
            :total="$permissions->total() . ' permission'"
            :resetUrl="route('super-admin.permissions.index')">

            <x-slot name="actions">
                <x-button type="button" @click="openCreateModal = true" size="sm" icon="fas fa-plus">
                    Tambah Permission
                </x-button>
            </x-slot>

            <x-slot name="filter_inputs">
                <!-- Order By -->
                <div class="w-full sm:w-44">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Urutan
                    </label>
                    <select name="orderBy"
                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" selected>Pilih Order By</option>
                        <option value="desc" @selected(request('orderBy') === 'desc')>Terbaru</option>
                        <option value="asc" @selected(request('orderBy') === 'asc')>Terlama</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div class="w-full sm:w-44">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Data per Halaman
                    </label>
                    <select name="per_page"
                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ([10, 20, 50, 100] as $page)
                            <option value="{{ $page }}" @selected(request('per_page') == $page)>
                                {{ $page }} / Halaman
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search -->
                <div class="flex-1 min-w-[240px] w-full">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Pencarian
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th class="w-16">No.</x-table.th>
                        <x-table.th>Nama Permission</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>

                <tbody id="admin-table-body" class="divide-y divide-slate-200">
                    @forelse ($permissions as $key => $permission)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td>
                                <p class="text-slate-600">{{ $key + $permissions->firstItem() }}</p>
                            </x-table.td>
                            <x-table.td>
                                <p class="text-slate-600">{{ $permission->name }}</p>
                            </x-table.td>
                            <x-table.td align="center">
                                <x-table.action>
                                    <li>
                                        <button type="button" @click="openEdit('{{ $permission->uuid }}', '{{ $permission->name }}', '{{ route('super-admin.permissions.update', $permission->uuid) }}')"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-left cursor-pointer">Edit</button>
                                    </li>
                                    <li>
                                        <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                            <x-modal-delete :id="$permission->uuid"
                                                message="Are you sure delete Permission" :item-name="$permission->name"
                                                :route="route(
                                                    'super-admin.permissions.destroy',
                                                    $permission->uuid,
                                                )" />
                                        </div>
                                    </li>
                                </x-table.action>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="3" align="center" class="py-12">
                                <p class="text-sm text-slate-500">Tidak ada data Permission</p>
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table.table>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $permissions->links() }}
            </div>
        </x-dashboard::filter-card>

        <!-- Modal Tambah Permission -->
        <template x-teleport="body">
            <template x-if="openCreateModal">
                <div class="fixed inset-0 z-50 flex items-center justify-center" @keydown.escape.window="openCreateModal = false">
                    <!-- Backdrop -->
                    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="openCreateModal = false"></div>

                    <!-- Modal Content -->
                    <div x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="relative w-full max-w-md p-6 bg-white border border-slate-100 rounded-2xl shadow-xl z-10">
                        
                        <!-- Close Button -->
                        <button type="button" @click="openCreateModal = false"
                            class="absolute top-4 end-4 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg w-8 h-8 inline-flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Title -->
                        <div class="mb-5">
                            <h3 class="text-lg font-bold text-slate-900">Tambah Permission Baru</h3>
                            <p class="text-xs text-slate-500 mt-1">Buat hak akses baru untuk sistem e-learning</p>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('super-admin.permissions.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <x-form.input name="name" id="modal_permission_name" label="Nama Permission" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama permission (cth: user-create)" helper="Format disarankan menggunakan pemisah strip (cth: modul-aksi)" />

                            <!-- Footer Actions -->
                            <div class="flex items-center justify-end gap-3 pt-2">
                                <x-button type="button" @click="openCreateModal = false" variant="white">
                                    Batal
                                </x-button>
                                <x-button type="submit">
                                    Simpan Permission
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </template>

        <!-- Modal Edit Permission -->
        <template x-teleport="body">
            <template x-if="openEditModal">
                <div class="fixed inset-0 z-50 flex items-center justify-center" @keydown.escape.window="openEditModal = false">
                    <!-- Backdrop -->
                    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="openEditModal = false"></div>

                    <!-- Modal Content -->
                    <div x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="relative w-full max-w-md p-6 bg-white border border-slate-100 rounded-2xl shadow-xl z-10">
                        
                        <!-- Close Button -->
                        <button type="button" @click="openEditModal = false"
                            class="absolute top-4 end-4 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg w-8 h-8 inline-flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Title -->
                        <div class="mb-5">
                            <h3 class="text-lg font-bold text-slate-900">Ubah Permission</h3>
                            <p class="text-xs text-slate-500 mt-1">Ubah rincian hak akses sistem</p>
                        </div>

                        <!-- Form -->
                        <form :action="editPermissionAction" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')
                            
                            <!-- Hidden input to track edit UUID for validation redirects -->
                            <input type="hidden" name="edit_id" :value="editPermissionId">

                            <x-form.input name="name" id="modal_edit_permission_name" label="Nama Permission" x-model="editPermissionName" required autofocus placeholder="Masukkan nama permission (cth: user-create)" helper="Format disarankan menggunakan pemisah strip (cth: modul-aksi)" />

                            <!-- Footer Actions -->
                            <div class="flex items-center justify-end gap-3 pt-2">
                                <x-button type="button" @click="openEditModal = false" variant="white">
                                    Batal
                                </x-button>
                                <x-button type="submit">
                                    Simpan Perubahan
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </template>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>
