<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')

    {{-- Vite CSS --}}
    {{-- {{ module_vite('build-landingpage', 'resources/assets/sass/app.scss') }} --}}
</head>

<body class="bg-gray-50">
    {{ $slot }}
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
    {!! ToastMagic::scripts() !!}
    @stack('scripts')
    {{-- Vite JS --}}
    {{-- {{ module_vite('build-landingpage', 'resources/assets/js/app.js') }} --}}
</body>

</html>
