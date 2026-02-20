<nav class="fixed top-0 z-50 w-full bg-white border-b border-slate-200">
    <div class="px-3 py-3 lg:px-5 h-16 flex items-center">
        <div class="flex justify-between items-center w-full">

            <!-- Left -->
            <div class="flex items-center">
                <!-- Toggle Sidebar (Mobile) -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="sm:hidden p-2 rounded-lg cursor-pointer hover:bg-gray-200 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10" />
                    </svg>
                </button>

                <!-- Logo -->
                <a href="{{ route('landingpage.index') }}" class="flex items-center ms-2">
                    <img src="{{ asset('images/logo.png') }}" alt="SIRENATA Logo" class="h-8 w-auto">
                    <span class="text-lg font-semibold ms-2" style="color: #13416B;">SIRENATA</span>
                </a>
            </div>

            <!-- Right -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="rounded-full overflow-hidden focus:outline-none cursor-pointer">
                    <img class="w-8 h-8 rounded-full"
                        src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=6366f1&color=fff"
                        alt="user">
                </button>

                <!-- Dropdown -->
                <div x-show="open" @click.outside="open = false" x-transition x-cloak
                    class="absolute right-0 top-full mt-2 bg-gray-50 border border-slate-200 rounded-lg shadow-lg w-44 z-50">
                    <div class="px-4 py-3 border-b border-slate-100">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    </div>
                    <ul class="p-2 text-sm">
                        <li>
                            <a href="#" class="block p-2 rounded hover:bg-purple-200">Profil</a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="flex w-full cursor-pointer items-center gap-2 rounded px-3 py-2 text-sm font-medium text-gray-700 hover:bg-purple-200 hover:text-purple-900 transition text-left">
                                    Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</nav>
