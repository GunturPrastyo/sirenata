<ul class="space-y-3 px-3 font-medium text-sm">
    <!-- Dashboard -->
    <li>
        <a href="{{ route('admin-province.dashboard') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin-province.dashboard')
                ? 'text-[#13416B] bg-slate-200/70 font-bold'
                : 'text-slate-600 hover:bg-slate-100 hover:text-[#13416B]' }}">
            <svg class="w-[22px] h-[22px] shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3v8h7.975A7.5 7.5 0 0 0 13.5 3Z" />
            </svg>
            <span class="ms-3.5">Dashboard</span>
        </a>
    </li>

    <!-- Proyek -->
    <li>
        <a href="{{ route('admin-province.project.index') }}" 
            class="flex items-center px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin-province.project.*')
                ? 'text-[#13416B] bg-slate-200/70 font-bold'
                : 'text-slate-600 hover:bg-slate-100 hover:text-[#13416B]' }}">
            <svg class="w-[22px] h-[22px] shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-5m-4 0V5a2 2 0 1 1 4 0v1m-4 0a2 2 0 1 0 4 0m-5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 0 0-2.83 2M15 11h3m-3 4h2" />
            </svg>
            <span class="ms-3.5">Proyek</span>
        </a>
    </li>

    <!-- Dropdown: Pelaporan -->
    <li x-data="{ open: {{ request()->routeIs('admin-province.rtkdp*', 'admin-province.laporan*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
            class="flex items-center cursor-pointer w-full px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin-province.rtkdp*', 'admin-province.laporan*')
                ? 'text-[#13416B] bg-slate-200/70 font-bold'
                : 'text-slate-600 hover:bg-slate-100 hover:text-[#13416B]' }}">
            <svg class="w-[22px] h-[22px] shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
            </svg>

            <span class="flex-1 ms-3.5 text-left">Pelaporan</span>

            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <ul x-show="open" x-collapse x-cloak class="mt-2 space-y-1 pl-6">
            <li>
                <a href="{{ route('admin-province.rtkdp.index') }}"
                    class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('admin-province.rtkdp*')
                        ? 'text-[#13416B] bg-[#13416B]/10 font-bold'
                        : 'text-slate-500 hover:bg-slate-100 hover:text-[#13416B]' }}">
                    Rekapitulasi RTK Provinsi
                </a>
            </li>

            <li>
                <a href="{{ route('admin-province.laporan.index') }}"
                    class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('admin-province.laporan*')
                        ? 'text-[#13416B] bg-[#13416B]/10 font-bold'
                        : 'text-slate-500 hover:bg-slate-100 hover:text-[#13416B]' }}">
                    Rekapitulasi RTK Keseluruhan
                </a>
            </li>
        </ul>
    </li>

    <!-- Dropdown: Rekapitulasi SDM -->
    <li x-data="{ open: {{ request()->routeIs('admin-province.rekapitulasi*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
            class="flex items-center cursor-pointer w-full px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin-province.rekapitulasi*')
                ? 'text-[#13416B] bg-slate-200/70 font-bold'
                : 'text-slate-600 hover:bg-slate-100 hover:text-[#13416B]' }}">
            <svg class="w-[22px] h-[22px] shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
            </svg>

            <span class="flex-1 ms-3.5 text-left">Rekapitulasi SDM</span>

            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <ul x-show="open" x-collapse x-cloak class="mt-2 space-y-1 pl-6">
            <li>
                <a href="{{ route('admin-province.rekapitulasi.rekap-user-province') }}"
                    class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('admin-province.rekapitulasi.rekap-user-province*')
                        ? 'text-[#13416B] bg-[#13416B]/10 font-bold'
                        : 'text-slate-500 hover:bg-slate-100 hover:text-[#13416B]' }}">
                    Rekapitulasi SDM Provinsi
                </a>
            </li>
            <li>
                <a href="{{ route('admin-province.rekapitulasi.index') }}"
                    class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('admin-province.rekapitulasi.index*')
                        ? 'text-[#13416B] bg-[#13416B]/10 font-bold'
                        : 'text-slate-500 hover:bg-slate-100 hover:text-[#13416B]' }}">
                    Rekapitulasi SDM Kab/Kota
                </a>
            </li>
        </ul>
    </li>

    <!-- Pemanfaatan RTKD -->
    <li>
        <a href="{{ route('admin-province.pemanfaatan-rtkd.index') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin-province.pemanfaatan-rtkd*')
                ? 'text-[#13416B] bg-slate-200/70 font-bold'
                : 'text-slate-600 hover:bg-slate-100 hover:text-[#13416B]' }}">
            <svg class="w-[22px] h-[22px] shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-6 9l2 2 4-4" />
            </svg>
            <span class="ms-3.5">Pemanfaatan RTKD</span>
        </a>
    </li>

   
</ul>