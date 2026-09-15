<ul class="space-y-3 px-3 font-medium text-sm">
    <!-- Dashboard -->
    <li>
        <a href="{{ route('super-admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('super-admin.dashboard')
                ? 'text-[#13416B] bg-slate-200/70 font-bold'
                : 'text-slate-600 hover:bg-slate-100 hover:text-[#13416B]' }}">
            <span class="w-7 shrink-0 flex items-center justify-center">
                <i class="fas fa-home text-lg"></i>
            </span>
            <span class="ms-3 text-[15px]">Dashboard</span>
        </a>
    </li>

    <!-- Manajemen User (Dropdown) -->
    <li x-data="{ open: {{ request()->routeIs('super-admin.user-management*', 'super-admin.roles.*', 'super-admin.permissions.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="flex items-center cursor-pointer w-full px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('super-admin.user-management*', 'super-admin.roles.*', 'super-admin.permissions.*')
                ? 'text-[#13416B] bg-slate-200/70 font-bold'
                : 'text-slate-600 hover:bg-slate-100 hover:text-[#13416B]' }}">
            <span class="w-7 shrink-0 flex items-center justify-center">
                <i class="fas fa-users-cog text-lg"></i>
            </span>

            <span class="flex-1 ms-3 text-left text-[15px]">Manajemen User</span>

            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <ul x-show="open" x-collapse x-cloak class="mt-2 space-y-1 pl-6">
            <li>
                <a href="{{ route('super-admin.user-management.index') }}" class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('super-admin.user-management*')
                        ? 'text-[#13416B] bg-[#13416B]/10 font-bold'
                        : 'text-slate-500 hover:bg-slate-100 hover:text-[#13416B]' }}">
                    Manajemen User
                </a>
            </li>

            <li>
                <a href="{{ route('super-admin.roles.index') }}" class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('super-admin.roles.*')
                        ? 'text-[#13416B] bg-[#13416B]/10 font-bold'
                        : 'text-slate-500 hover:bg-slate-100 hover:text-[#13416B]' }}">
                    Roles
                </a>
            </li>
            <li>
                <a href="{{ route('super-admin.permissions.index') }}" class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('super-admin.permissions.*')
                        ? 'text-[#13416B] bg-[#13416B]/10 font-bold'
                        : 'text-slate-500 hover:bg-slate-100 hover:text-[#13416B]' }}">
                    Permission
                </a>
            </li>
        </ul>
    </li>

    <!-- Instansi (Dropdown) -->
    <li x-data="{ open: {{ request()->routeIs('super-admin.lembaga.*', 'super-admin.instansi.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="flex items-center cursor-pointer w-full px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('super-admin.lembaga.*', 'super-admin.instansi.*')
                ? 'text-[#13416B] bg-slate-200/70 font-bold'
                : 'text-slate-600 hover:bg-slate-100 hover:text-[#13416B]' }}">
            <span class="w-7 shrink-0 flex items-center justify-center">
                <i class="fas fa-building text-lg"></i>
            </span>

            <span class="flex-1 ms-3 text-left text-[15px]">Instansi</span>

            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <ul x-show="open" x-collapse x-cloak class="mt-2 space-y-1 pl-6">
            <li>
                <a href="{{ route('super-admin.lembaga.index') }}" class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('super-admin.lembaga.*')
                        ? 'text-[#13416B] bg-[#13416B]/10 font-bold'
                        : 'text-slate-500 hover:bg-slate-100 hover:text-[#13416B]' }}">
                    Pusat
                </a>
            </li>
            <li>
                <a href="{{ route('super-admin.instansi.index') }}" class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('super-admin.instansi.*')
                        ? 'text-[#13416B] bg-[#13416B]/10 font-bold'
                        : 'text-slate-500 hover:bg-slate-100 hover:text-[#13416B]' }}">
                    Daerah
                </a>
            </li>
        </ul>
    </li>
</ul>