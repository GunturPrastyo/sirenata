<aside
    class="fixed top-0 left-0 z-40 w-64 h-full bg-white border-e border-gray-200
    transform -translate-x-full transition-transform duration-300
    sm:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : ''" @keydown.escape.window="sidebarOpen = false" x-cloak>
    <div class="h-full px-3 py-4 overflow-y-auto pt-20 sm:pt-4">

        <a href="#" class="flex items-center ps-2.5 mb-5">
            <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="logo">
            <span class="ms-2 text-lg font-semibold" style="color:#13416B;">SIRENATA</span>
        </a>

        <ul class="space-y-2 font-medium">
            @role('super-admin')
                @include('dashboard::partials.super-admin.sidebar-item')
            @endrole

            @role('admin-pusat')
                @include('dashboard::partials.admin-pusat.sidebar-item')
            @endrole

            @role('admin-province')
                @include('dashboard::partials.admin-province.sidebar-item')
            @endrole

            @role('admin-kab-kota')
                @include('dashboard::partials.admin-kab-kota.sidebar-item')
            @endrole

            @role('user')
                @include('dashboard::partials.user.sidebar-item')
            @endrole
        </ul>
    </div>
</aside>
