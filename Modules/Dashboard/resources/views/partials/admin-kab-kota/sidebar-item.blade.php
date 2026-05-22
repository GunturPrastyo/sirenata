<li>
    <a href="{{ route('admin-kab-kota.dashboard') }}" class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-kab-kota.dashboard')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
        <svg class="w-5 h-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.5 3v8h7.975A7.5 7.5 0 0 0 13.5 3Z" />
        </svg>
        <span class="ms-3">Dashboard</span>
    </a>
</li>

<li>
    <a href="{{ route('admin-kab-kota.project.index') }}" class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-kab-kota.project.*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#13416B]" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 6H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-5m-4 0V5a2 2 0 1 1 4 0v1m-4 0a2 2 0 1 0 4 0m-5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 0 0-2.83 2M15 11h3m-3 4h2" />
        </svg>
        <span class="ms-3">Proyek</span>
    </a>
</li>

<li x-data="{ open: {{ request()->routeIs('admin-kab-kota.rtkd*', 'admin-kab-kota.laporan*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="flex items-center cursor-pointer w-full px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-kab-kota.rtkd*', 'admin-kab-kota.laporan*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
        <svg class="w-5 h-5 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
        </svg>

        <span class="flex-1 ms-3 text-left">Pelaporan</span>

        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
        </svg>
    </button>

    <ul x-show="open" x-collapse x-cloak class="mt-1 space-y-1">
        <li>
            <a href="{{ route('admin-kab-kota.rtkd.index') }}" class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-xs
                {{ request()->routeIs('admin-kab-kota.rtkd*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
                Rekapitulasi Rencana Tenaga Kerja Kab/Kota
            </a>
        </li>
        <li>
            <a href="{{ route('admin-kab-kota.laporan.index') }}" class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-xs
                {{ request()->routeIs('admin-kab-kota.laporan*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
                Rencana Tenaga Kerja (RTK)
            </a>
        </li>

    </ul>
</li>

<li>
    <a href="{{ route('admin-kab-kota.rekapitulasi.index') }}" class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-kab-kota.rekapitulasi*')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#13416B]" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
        </svg>
        <span class="flex-1 ms-3 whitespace-nowrap">Rekapitulasi SDM</span>
    </a>
</li>

<li>
    <a href="{{ route('admin-kab-kota.help') }}" class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-kab-kota.help')
    ? 'text-[#13416B] bg-[#13416B]/30'
    : 'text-gray-600 hover:bg-[#13416B]/30 hover:text-[#13416B]' }}">
        <svg class="w-5 h-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span class="ms-3">Bantuan</span>
    </a>
</li>