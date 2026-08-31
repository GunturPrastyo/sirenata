<!-- Navbar berada di lapisan bawah Sidebar (z-40). -->
<nav class="fixed top-0 z-40 w-full bg-white border-b border-slate-200 transition-all duration-300" :class="sidebarOpen ? 'lg:pl-64' : 'pl-0'">
    
    <div class="px-4 sm:px-6 lg:px-8 py-3.5 sm:py-4 flex items-center justify-between w-full gap-3 sm:gap-6 min-h-[72px] sm:min-h-[80px]">

        <!-- Bagian Kiri & Tengah (Toggle + Searchbar) -->
        <div class="flex items-center flex-1 gap-3 sm:gap-5">
            
            @if(auth()->check() && auth()->user()->hasRole('user'))
                <!-- Toggle Sidebar KHUSUS USER (Hanya di Desktop karena mobile pakai Bottom Nav) -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="hidden lg:block p-2 sm:p-2.5 rounded-xl cursor-pointer text-slate-500 hover:bg-slate-100 hover:text-[#13416B] focus:outline-none transition-colors shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Searchbar KHUSUS USER (Lebar penuh di semua resolusi) -->
                <div class="flex-1 w-full max-w-4xl">
                    <div class="relative w-full group">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                            <i class="fas fa-search text-slate-400 group-focus-within:text-[#13416B] transition-colors text-sm sm:text-base"></i>
                        </div>
                        <input type="text" class="bg-slate-50 border border-slate-200 text-slate-900 text-xs sm:text-sm rounded-xl focus:ring-[#13416B] focus:border-[#13416B] block w-full ps-10 sm:ps-11 p-2.5 sm:p-3 transition-colors shadow-sm" placeholder="Cari modul atau buku...">
                    </div>
                </div>
            @else
                <!-- Toggle Sidebar SEMUA ROLE LAIN (Muncul di Mobile & Desktop) -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="block p-2 sm:p-2.5 rounded-xl cursor-pointer text-slate-500 hover:bg-slate-100 hover:text-[#13416B] focus:outline-none transition-colors shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            @endif

        </div>

        <!-- Bagian Kanan (Profil) -->
        @auth
            <div class="relative shrink-0" x-data="{ open: false }">
                <div class="flex items-center">
                    <button @click="open = !open"
                        class="rounded-full overflow-hidden focus:outline-none cursor-pointer border-2 border-transparent hover:border-[#13416B] transition-colors">
                        <img class="w-9 h-9 sm:w-11 sm:h-11 rounded-full object-cover"
                            src="https://ui-avatars.com/api/?name={{ auth()->user()->profile?->full_name ?? auth()->user()->name }}&background=13416b&color=fff"
                            alt="user">
                    </button>
                </div>

                <!-- Dropdown Profil -->
                <div x-show="open" @click.outside="open = false" x-transition x-cloak
                    class="absolute right-0 top-full mt-3 bg-white border border-slate-200 rounded-2xl shadow-xl w-64 z-50 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/80">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mt-1">{{ auth()->user()->roles->first()->name ?? 'User' }}</p>
                    </div>
                    <ul class="p-2 text-sm text-slate-700 space-y-1">
                        @role('super-admin')
                            <li><a href="{{ route('super-admin.profile') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-[#13416B]/10 hover:text-[#13416B] font-medium transition-colors"><i class="fas fa-user-circle w-4 text-center"></i> Profil</a></li>
                            <li><a href="{{ route('super-admin.dashboard') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-[#13416B]/10 hover:text-[#13416B] font-medium transition-colors"><i class="fas fa-laptop w-4 text-center"></i> Dashboard</a></li>
                        @endrole
                        @role('user')
                            <li><a href="{{ route('user.profile') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-[#13416B]/10 hover:text-[#13416B] font-medium transition-colors"><i class="fas fa-user-cog w-4 text-center"></i> Profil Saya</a></li>
                        @endrole

                        <li class="my-1 border-t border-slate-100"></li>
                        
                        <!-- MENU BANTUAN -->
                        <li>
                            <a href="{{ route('user.help') }}" class="flex items-center gap-3 p-2.5 rounded-xl text-slate-700 hover:bg-[#13416B]/10 hover:text-[#13416B] font-medium transition-colors">
                                <i class="fas fa-question-circle w-4 text-center text-slate-400"></i> Pusat Bantuan
                            </a>
                        </li>
                        
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="flex w-full cursor-pointer items-center gap-3 p-2.5 rounded-xl text-red-600 hover:bg-red-50 font-bold transition-colors text-left">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @endauth
    </div>
</nav>