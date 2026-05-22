<x-dashboard::layouts.dashboard title="Edit Role">

    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Manajemen Role', 'url' => route('super-admin.roles.index')], ['label' => 'Edit Role']]" />

        <x-validation-errors />
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-6 mb-6 card-hover">
            <form action="{{ route('super-admin.roles.update', $role->uuid) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="mb-8">
                    <x-form.input name="name" label="Name" value="{{ $role->name }}" required placeholder="Masukkan name" />
                </div>

                <div class="mb-8" x-data="{
                    toggleAll(state) {
                        document.querySelectorAll('input[name=\'permissions[]\']')
                            .forEach(cb => cb.checked = state);
                    }
                }">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Assign Permissions
                        </h3>
                        <div class="flex gap-3">
                            <button type="button" @click="toggleAll(true)"
                                class="text-sm cursor-pointer font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                                Pilih Semua
                            </button>
                            <span class="text-gray-300">|</span>
                            <button type="button" @click="toggleAll(false)"
                                class="text-sm cursor-pointer font-medium text-red-600 hover:text-red-700 transition-colors">
                                Batalkan Pilihan
                            </button>
                        </div>
                    </div>

                    <x-table.table plain>
                        <thead>
                            <tr>
                                <x-table.th>Permission Name</x-table.th>
                                <x-table.th align="center">Create</x-table.th>
                                <x-table.th align="center">Edit</x-table.th>
                                <x-table.th align="center">View</x-table.th>
                                <x-table.th align="center">Delete</x-table.th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($permissions as $module => $modulePermissions)
                                <tr>
                                    <x-table.td>
                                        <span class="font-medium text-gray-700">{{ $module }}</span>
                                    </x-table.td>

                                    @foreach (['create', 'edit', 'view', 'delete'] as $action)
                                        @php
                                            $permissionName = $module . '-' . $action;
                                            $exists = $modulePermissions->firstWhere('name', $permissionName);
                                        @endphp

                                        <x-table.td align="center">
                                            @if ($exists)
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $permissionName }}"
                                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                    {{ $role->hasPermissionTo($permissionName) ? 'checked' : '' }}>
                                            @else
                                                -
                                            @endif
                                        </x-table.td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </x-table.table>
                </div>

                <div class="flex flex-wrap gap-3 action-buttons form-section w-full">
                    <x-button type="submit" id="btn-submit" class="w-full">
                        Simpan Role
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard::layouts.dashboard>
