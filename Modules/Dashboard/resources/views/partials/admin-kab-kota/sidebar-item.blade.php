<ul class="space-y-3 px-3 font-medium text-sm">
    <!-- Dashboard -->
    <li>
        <a href="{{ route('admin-kab-kota.dashboard') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin-kab-kota.dashboard')
                ? 'text-[#13416B] bg-slate-100 font-bold'
                : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
            <span class="w-7 shrink-0 flex items-center justify-center">
                <i class="fas fa-home text-lg"></i>
            </span>
            <span class="ms-3 text-[15px]">Dashboard</span>
        </a>
    </li>

    <!-- Proyek -->
    <li>
        <a href="{{ route('admin-kab-kota.project.index') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin-kab-kota.project.*')
                ? 'text-[#13416B] bg-slate-100 font-bold'
                : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
            <span class="w-7 shrink-0 flex items-center justify-center">
                <i class="fas fa-briefcase text-lg"></i>
            </span>
            <span class="ms-3 text-[15px]">Proyek</span>
        </a>
    </li>

    <!-- Dropdown: Pelaporan -->
    <li x-data="{ open: {{ request()->routeIs('admin-kab-kota.rtkd*', 'admin-kab-kota.laporan*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
            class="flex items-center cursor-pointer w-full px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin-kab-kota.rtkd*', 'admin-kab-kota.laporan*')
                ? 'text-[#13416B] bg-slate-100 font-bold'
                : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
            <span class="w-7 shrink-0 flex items-center justify-center">
                <i class="fas fa-file-alt text-lg"></i>
            </span>

            <span class="flex-1 ms-3 text-left text-[15px]">Pelaporan</span>

            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <ul x-show="open" x-collapse x-cloak class="mt-2 space-y-1 pl-6">
            <li>
                <a href="{{ route('admin-kab-kota.rtkd.index') }}"
                    class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('admin-kab-kota.rtkd*')
                        ? 'text-[#13416B] bg-slate-100 font-bold'
                        : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
                    Rekapitulasi Rencana Tenaga Kerja Kab/Kota
                </a>
            </li>
            <li>
                <a href="{{ route('admin-kab-kota.laporan.index') }}"
                    class="flex items-center pl-7 px-4 py-2.5 rounded-lg transition-colors text-[13px]
                    {{ request()->routeIs('admin-kab-kota.laporan*')
                        ? 'text-[#13416B] bg-slate-100 font-bold'
                        : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
                    Rencana Tenaga Kerja (RTK)
                </a>
            </li>
        </ul>
    </li>

    <!-- Rekapitulasi SDM -->
    <li>
        <a href="{{ route('admin-kab-kota.rekapitulasi.index') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all duration-200
            {{ request()->routeIs('admin-kab-kota.rekapitulasi*')
                ? 'text-[#13416B] bg-slate-100 font-bold'
                : 'text-slate-500 hover:bg-slate-50 hover:text-[#13416B]' }}">
            <span class="w-7 shrink-0 flex items-center justify-center">
                <i class="fas fa-users text-lg"></i>
            </span>
            <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Rekapitulasi SDM</span>
        </a>
    </li>
</ul>
