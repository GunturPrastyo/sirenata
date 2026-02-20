<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('landingpage.index') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="SIRENATA Logo" class="h-10 w-auto">
                <span class="text-xl font-bold" style="color: #13416B;">SIRENATA</span>
            </a>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 text-gray-700 hover:text-gray-900 font-semibold rounded-lg hover:bg-gray-100 transition-all">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-all">
                        Daftar
                    </a>
                @endguest

                @auth
                    @if (request()->routeIs('portal-dashboard'))
                        <a href="{{ route('landingpage.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                            ← Kembali ke Beranda
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-5 py-2.5 cursor-pointer bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md shadow-md transition-all">
                                Log Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('portal-dashboard') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                            Masuk ke Portal
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>
