<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>

<body class="bg-gray-50 min-h-screen">
    <x-landingpage::navbar />
    {{ $slot }}
    <x-landingpage::footer />
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
    {!! ToastMagic::scripts() !!}
    @stack('scripts')
</body>

</html>
