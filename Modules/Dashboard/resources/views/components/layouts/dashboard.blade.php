<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>

<!-- Alpine.js mendeteksi lebar layar. Default tertutup di Tablet & Mobile, terbuka di Desktop -->
<body x-data="{ sidebarOpen: window.innerWidth >= 1024 }" 
      @resize.window="sidebarOpen = window.innerWidth >= 1024" 
      class="bg-gray-50 min-h-screen">
      
    <!-- Navbar -->
    @include('dashboard::partials.navbar')
    
    <!-- Overlay (Mobile & Tablet) -->
    @include('dashboard::partials.overlay')

    <!-- Sidebar -->
    @include('dashboard::partials.sidebar')

    <!-- Main Content (Dinamis merentang penuh / w-full jika sidebar ditutup) -->
    <main class="p-2 sm:p-4 mt-24 md:mt-16 transition-all duration-300" :class="sidebarOpen ? 'lg:ml-64' : 'ml-0'">
        {{ $slot }}
    </main>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

    {!! ToastMagic::scripts() !!}
    @stack('scripts')
</body>

</html>