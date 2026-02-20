<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <title>@yield('title')</title> --}}
    @include('partials.error-head')
    {{-- <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <!-- Styles -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #f59e0b 0%, #dc2626 100%);
        }

        .error-illustration {
            animation: shake 0.5s ease-in-out infinite alternate;
        }

        @keyframes shake {
            0% {
                transform: rotate(-3deg);
            }

            100% {
                transform: rotate(3deg);
            }
        }

        .button-hover {
            transition: all 0.3s ease;
        }

        .button-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .gradient-bg-blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .error-illustration {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .gradient-bg-red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .error-illustration {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(0.95);
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">

    <div class="max-w-2xl w-full">

        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('landingpage.index') }}" class="inline-flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="SIRENATA Logo" class="h-12 w-auto">
                <span class="text-2xl font-bold ml-3" style="color: #13416B;">SIRENATA</span>
            </a>
        </div>

        <!-- Error Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            <!-- Header with Gradient -->
            @yield('header')

            <!-- Content -->
            <div class="px-8 py-10">
                @yield('content')

                <!-- Action Buttons -->
                <div class="space-y-3">
                    @yield('action-buttons')
                </div>

                <!-- Additional Info -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 text-center">
                        Jika Anda yakin ini adalah kesalahan sistem, silakan
                        <a href="#" class="text-orange-600 hover:text-orange-800 font-medium">
                            hubungi administrator sistem
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-500 text-sm mt-8">
            © 2026 SIRENATA - Sistem Informasi Rencana Tenaga Kerja Nasional
        </p>
    </div>
</body>

</html>
