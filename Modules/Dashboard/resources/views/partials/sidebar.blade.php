<!-- Sidebar berada di lapisan atas (z-50) sehingga menimpa Navbar -->
<aside
    class="fixed top-0 left-0 z-50 w-64 h-full bg-white border-e border-slate-200 transform transition-transform duration-300"
    :class="sidebarOpen ? 'translate-x-0 shadow-2xl lg:shadow-none' : '-translate-x-full'" 
    @keydown.escape.window="sidebarOpen = false" x-cloak>
    
    <div class="h-full py-4 overflow-y-auto">

        <!-- Logo dipindahkan ke dalam Sidebar -->
        <div class="flex items-center justify-between px-5 mb-8 mt-2">
            <a href="{{ route('landingpage.index') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="logo">
                <span class="ms-2 text-xl font-extrabold tracking-tight" style="color:#13416B;">SIRENATA</span>
            </a>
            
            <!-- Tombol Close Sidebar Manual Khusus Mobile/Tablet -->
            <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-slate-100 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <ul class="space-y-1.5 px-3 font-medium">
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