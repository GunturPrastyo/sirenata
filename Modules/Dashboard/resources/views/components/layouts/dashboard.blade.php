<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    @include('partials.head')
</head>

<body x-data="{ sidebarOpen: false }" class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('dashboard::partials.navbar')
    <!-- Overlay (Mobile) -->
    @include('dashboard::partials.overlay')

    <!-- Sidebar -->
    @include('dashboard::partials.sidebar')

    <!-- Main Content -->
    <main class="p-2 sm:p-4 sm:ml-64 mt-14">
        {{ $slot }}
    </main>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

    {!! ToastMagic::scripts() !!}
    @stack('scripts')
</body>

</html>
