<li>
    <a href="{{ route('super-admin.dashboard') }}" class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('super-admin.dashboard')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#13416B]" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
        </svg>
        <span class="ms-3">Dashboard</span>
    </a>
</li>

<li
    x-data="{ open: {{ request()->routeIs('super-admin.user-management*', 'super-admin.roles.*', 'super-admin.permissions.*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="flex items-center cursor-pointer w-full px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('super-admin.user-management*', 'super-admin.roles.*', 'super-admin.permissions.*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#13416B]" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
        </svg>

        <span class="flex-1 ms-3 text-left">Manajemen User</span>

        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
        </svg>
    </button>

    <ul x-show="open" x-collapse x-cloak class="mt-1 space-y-1">
        <li>
            <a href="{{ route('super-admin.user-management.index') }}" class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-sm
                {{ request()->routeIs('super-admin.user-management*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
                Manajemen User
            </a>
        </li>

        <li>
            <a href="{{ route('super-admin.roles.index') }}" class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-sm
                {{ request()->routeIs('super-admin.roles.*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
                Roles
            </a>
        </li>
        <li>
            <a href="{{ route('super-admin.permissions.index') }}" class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-sm
                {{ request()->routeIs('super-admin.permissions.*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
                Permission
            </a>
        </li>
    </ul>
</li>

<li
    x-data="{ open: {{ request()->routeIs('super-admin.lembaga.*', 'super-admin.instansi.*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="flex items-center cursor-pointer w-full px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('super-admin.lembaga.*', 'super-admin.instansi.*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#13416B]" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 7h16M4 12h16m-7 5h7" />
        </svg>

        <span class="flex-1 ms-3 text-left">Instansi</span>

        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
        </svg>
    </button>

    <ul x-show="open" x-collapse x-cloak class="mt-1 space-y-1">
        <li>
            <a href="{{ route('super-admin.lembaga.index') }}" class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-sm
                {{ request()->routeIs('super-admin.lembaga.*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
                Pusat
            </a>
        </li>
        <li>
            <a href="{{ route('super-admin.instansi.index') }}" class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-sm
                {{ request()->routeIs('super-admin.instansi.*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
                Daerah
            </a>
        </li>
    </ul>
</li>