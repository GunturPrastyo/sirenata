<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>

<body>
    {{ $slot }}

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
    {!! ToastMagic::scripts() !!}
    @stack('scripts')
</body>

</html>
