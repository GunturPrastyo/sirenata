<x-dashboard::layouts.dashboard title="Create User">

    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Manajemen User', 'url' => route('super-admin.user-management.index')], ['label' => 'Create User']]" />

        <livewire:dashboard.super-admin.user-management-create />
    </div>

</x-dashboard::layouts.dashboard>
