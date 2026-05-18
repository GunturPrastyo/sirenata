<li>
    <a href="{{ route('admin-pusat.dashboard') }}"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-pusat.dashboard')
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
    <a href="{{ route('admin-pusat.project.index') }}"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('project.*')
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

<li x-data="{ open: {{ request()->routeIs('admin-pusat.rtkn*', 'admin-pusat.rtkd*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
        class="flex items-center cursor-pointer w-full px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-pusat.rtkn*', 'admin-pusat.rtkd*')
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
            <a href="{{ route('admin-pusat.rtkn.index') }}"
                class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-xs
                {{ request()->routeIs('admin-pusat.rtkn.index*')
                    ? 'text-indigo-600 bg-purple-100'
                    : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
                Rekapitulasi Rencana Tenaga Kerja Nasional
            </a>
        </li>
        <li>
            <a href="{{ route('admin-pusat.rtkd.index') }}"
                class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-xs
                {{ request()->routeIs('admin-pusat.rtkd*')
                    ? 'text-indigo-600 bg-purple-100'
                    : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
                Rekapitulasi Rencana Tenaga Kerja Provinsi
            </a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('admin-pusat.rekapitulasi.index') }}"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-pusat.rekapitulasi*')
            ? 'text-indigo-600 bg-purple-100'
            : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-indigo-600" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
        </svg>
        <span class="flex-1 ms-3 whitespace-nowrap">Rekapitulasi SDM</span>
    </a>
</li>

<li>
    <a href="{{ route('admin-pusat.survey-periods.index') }}"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-pusat.survey-periods.*')
            ? 'text-indigo-600 bg-purple-100'
            : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="w-5 h-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span class="ms-3">Periode Survei</span>
    </a>
</li>

<li>
    <a href="{{ route('admin-pusat.hasil-pemanfaatan-rtkd.index') }}"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-pusat.hasil-pemanfaatan-rtkd.*')
            ? 'text-indigo-600 bg-purple-100'
            : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        <span class="ms-3 text-sm">Hasil Kuesioner Pemanfaatan</span>
    </a>
</li>

<li>
    <a href="#"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->is('admin-pusat/notification')
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

<li x-data="{ open: {{ request()->routeIs('admin-pusat.library-types.*', 'admin-pusat.libraries.*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
        class="flex items-center cursor-pointer w-full px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('admin-pusat.library-types.*', 'admin-pusat.libraries.*')
            ? 'text-indigo-600 bg-purple-100'
            : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="w-5 h-5 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6v13m0-13c-2.819-.831-4.715-1-7-1v14c2.285 0 4.181.169 7 1m0-14c2.819-.831 4.715-1 7-1v14c-2.285 0-4.181.169-7 1" />
        </svg>

        <span class="flex-1 ms-3 text-left">Perpustakaan</span>

        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
        </svg>
    </button>

    <ul x-show="open" x-collapse class="mt-1 space-y-1">
        <li>
            <a href="{{ route('admin-pusat.library-types.index') }}"
                class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-xs
                {{ request()->routeIs('admin-pusat.library-types.*')
                    ? 'text-indigo-600 bg-purple-100'
                    : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
                Tipe Perpustakaan
            </a>
        </li>
        <li>
            <a href="{{ route('admin-pusat.libraries.index') }}"
                class="flex items-center pl-10 px-2 py-1.5 rounded-md transition text-xs
                {{ request()->routeIs('admin-pusat.libraries.*')
                    ? 'text-indigo-600 bg-purple-100'
                    : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
                Materi Perpustakaan
            </a>
        </li>
    </ul>
</li>

<li>
    <a href="{{ route('admin-pusat.faq.index') }}"
        class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('faq.*')
            ? 'text-indigo-600 bg-purple-100'
            : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="w-5 h-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span class="ms-3">Bantuan / FAQ</span>
    </a>
</li>
