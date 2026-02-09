<li>
    <a href="{{ route('admin-kab-kota.dashboard') }}"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-kab-kota.dashboard')
            ? 'text-indigo-600 bg-purple-100'
            : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
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
    <a href="#"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-pusat.help')
            ? 'text-indigo-600 bg-purple-100'
            : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-indigo-600" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 6H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-5m-4 0V5a2 2 0 1 1 4 0v1m-4 0a2 2 0 1 0 4 0m-5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 0 0-2.83 2M15 11h3m-3 4h2" />
        </svg>
        <span class="ms-3">Proyek</span>
    </a>
</li>

{{-- <li x-data="{ open: {{ request()->routeIs('admin-province.rtkdp*,admin-province.laporan.index') ? 'true' : 'false' }} }">
    <button @click="open = !open"
        class="flex items-center cursor-pointer w-full px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-province.rtkdp*', 'admin-province.laporan.index')
            ? 'text-indigo-600 bg-purple-100'
            : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="w-5 h-5 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
        </svg>

        <span class="flex-1 ms-3 text-left">Pelaporan</span>

        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
        </svg>
    </button>

    <ul x-show="open" x-collapse class="mt-1 space-y-1">
        <li>
            <a href="{{ route('admin-province.laporan.index') }}"
                class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-sm
                {{ request()->routeIs('admin-province.laporan*')
                    ? 'text-indigo-600 bg-purple-100'
                    : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
                Rencana Tenaga Kerja (RTK)
            </a>
        </li>

        <li>
            <a href="{{ route('admin-province.rtkdp.index') }}"
                class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-sm
                {{ request()->routeIs('admin-province.rtkdp*')
                    ? 'text-indigo-600 bg-purple-100'
                    : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
                Rencana Tenaga Kerja Provinsi (RTKP)
            </a>
        </li>
    </ul>
</li> --}}

<li>
    <a href="#"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-pusat.notification')
            ? 'text-indigo-600 bg-purple-100'
            : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-indigo-600" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 5.365V3m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175 0 .593 0 1.292-.538 1.292H5.538C5 18 5 17.301 5 16.708c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 12 5.365ZM8.733 18c.094.852.306 1.54.944 2.112a3.48 3.48 0 0 0 4.646 0c.638-.572 1.236-1.26 1.33-2.112h-6.92Z" />
        </svg>
        <span class="ms-3">Notifikasi</span>
    </a>
</li>

<li>
    <a href="#"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-pusat.help')
            ? 'text-indigo-600 bg-purple-100'
            : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="w-5 h-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0Z" />
        </svg>
        <span class="ms-3">Bantuan</span>
    </a>
</li>
