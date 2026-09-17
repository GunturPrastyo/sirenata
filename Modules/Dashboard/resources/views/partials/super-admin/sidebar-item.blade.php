<ul class="space-y-2 px-4 font-medium text-sm">
    <!-- Dashboard -->
    <li>
        <a href="{{ route('super-admin.dashboard') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-200
            {{ request()->routeIs('super-admin.dashboard')
                ? 'text-[#13416B] bg-slate-100 font-bold'
                : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
            <span class="w-6 shrink-0 flex items-center justify-center">
                <i class="fas fa-home text-[1.1rem]"></i>
            </span>
            <span class="text-[15px]">Dashboard</span>
        </a>
    </li>

    <!-- Manajemen User (Dropdown) -->
    <li x-data="{ open: {{ request()->routeIs('super-admin.user-management*', 'super-admin.roles.*', 'super-admin.permissions.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="flex items-center gap-4 w-full px-4 py-3.5 rounded-2xl transition-all duration-200
            {{ request()->routeIs('super-admin.user-management*', 'super-admin.roles.*', 'super-admin.permissions.*')
                ? 'text-[#13416B] bg-slate-100 font-bold'
                : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
            <span class="w-6 shrink-0 flex items-center justify-center">
                <i class="fas fa-users-cog text-[1.1rem]"></i>
            </span>

            <span class="flex-1 text-left text-[15px]">Manajemen User</span>

            <svg class="w-4 h-4 shrink-0 transition-transform duration-200 text-slate-400" :class="{ 'rotate-180': open }"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <ul x-show="open" x-collapse x-cloak class="mt-1 space-y-1">
            <li>
                <a href="{{ route('super-admin.user-management.index') }}" class="block w-full pl-[3.25rem] pr-4 py-3 rounded-2xl transition-colors text-[14px]
                    {{ request()->routeIs('super-admin.user-management*')
                        ? 'text-[#13416B] bg-slate-100 font-bold'
                        : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
                    Manajemen User
                </a>
            </li>

            <li>
                <a href="{{ route('super-admin.roles.index') }}" class="block w-full pl-[3.25rem] pr-4 py-3 rounded-2xl transition-colors text-[14px]
                    {{ request()->routeIs('super-admin.roles.*')
                        ? 'text-[#13416B] bg-slate-100 font-bold'
                        : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
                    Roles
                </a>
            </li>
            <li>
                <a href="{{ route('super-admin.permissions.index') }}" class="block w-full pl-[3.25rem] pr-4 py-3 rounded-2xl transition-colors text-[14px]
                    {{ request()->routeIs('super-admin.permissions.*')
                        ? 'text-[#13416B] bg-slate-100 font-bold'
                        : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
                    Permission
                </a>
            </li>
        </ul>
    </li>

    <!-- Manajemen Instansi (Dropdown) -->
    <li x-data="{ open: {{ request()->routeIs('super-admin.lembaga.*', 'super-admin.instansi.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="flex items-center gap-4 w-full px-4 py-3.5 rounded-2xl transition-all duration-200
            {{ request()->routeIs('super-admin.lembaga.*', 'super-admin.instansi.*')
                ? 'text-[#13416B] bg-slate-100 font-bold'
                : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
            <span class="w-6 shrink-0 flex items-center justify-center">
                <i class="fas fa-building text-[1.1rem]"></i>
            </span>

            <span class="flex-1 text-left text-[15px]">Manajemen Instansi</span>

            <svg class="w-4 h-4 shrink-0 transition-transform duration-200 text-slate-400" :class="{ 'rotate-180': open }"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <ul x-show="open" x-collapse x-cloak class="mt-1 space-y-1">
            <li>
                <a href="{{ route('super-admin.lembaga.index') }}" class="block w-full pl-[3.25rem] pr-4 py-3 rounded-2xl transition-colors text-[14px]
                    {{ request()->routeIs('super-admin.lembaga.*')
                        ? 'text-[#13416B] bg-slate-100 font-bold'
                        : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
                    Pusat
                </a>
            </li>
            <li>
                <a href="{{ route('super-admin.instansi.index') }}" class="block w-full pl-[3.25rem] pr-4 py-3 rounded-2xl transition-colors text-[14px]
                    {{ request()->routeIs('super-admin.instansi.*')
                        ? 'text-[#13416B] bg-slate-100 font-bold'
                        : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
                    Daerah
                </a>
            </li>
        </ul>
    </li>
</ul>