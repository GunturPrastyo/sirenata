<!-- CSS Global Khusus untuk memberikan jarak aman konten bawah agar tidak tertimpa menu bawah di HP -->
<style>
    @media (max-width: 1023px) {
        body { padding-bottom: 76px !important; }
    }
</style>

<!-- Komponen Sidebar ini akan otomatis berubah menjadi Bottom Nav pada resolusi HP & Tablet -->
<aside
    class="fixed z-50 bg-white transition-all duration-300
           /* MOBILE & TABLET (Menjadi Bottom Navigation) */
           bottom-0 left-0 w-full h-[70px] sm:h-[76px] border-t border-slate-200 shadow-[0_-4px_15px_rgba(0,0,0,0.04)] translate-y-0
           /* DESKTOP (Kembali menjadi Left Sidebar) */
           lg:top-0 lg:bottom-auto lg:w-64 lg:h-full lg:border-e lg:border-t-0 lg:shadow-none"
    :class="sidebarOpen ? 'lg:translate-x-0' : 'lg:-translate-x-full'"
    @keydown.escape.window="sidebarOpen = false" x-cloak>
    
    <div class="h-full w-full lg:py-4 flex flex-row lg:flex-col overflow-hidden">

        <!-- Logo (HANYA MUNCUL DI DESKTOP) -->
        <div class="hidden lg:flex items-center justify-between px-6 mb-8 mt-2 shrink-0">
            <a href="{{ route('landingpage.index') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto" alt="logo">
                <span class="ms-2 text-xl font-extrabold tracking-tight" style="color:#13416B;">SIRENATA</span>
            </a>
        </div>

        <!-- Menu Wrapper (Meluber menyamping di HP, vertikal di Desktop) -->
        <div class="flex-1 w-full h-full lg:h-auto lg:overflow-y-auto overflow-x-auto scrollbar-hide flex items-center justify-around lg:justify-start lg:block pb-safe">
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
        </div>
    </div>
</aside>