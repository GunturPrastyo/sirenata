<x-dashboard::layouts.dashboard title="User Management">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :items="[['label' => 'Manajemen User']]" />

        <livewire:dashboard.super-admin.user-management-table />
    </div>
</x-dashboard::layouts.dashboard>
