<ul class="space-y-1.5 px-3 font-medium text-sm">
    <!-- Dashboard -->
    <li>
        <a href="{{ route('admin-pusat.dashboard') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition
            {{ request()->routeIs('admin-pusat.dashboard')
                ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3v8h7.975A7.5 7.5 0 0 0 13.5 3Z" />
            </svg>
            <span class="ms-3 text-sm">Dashboard</span>
        </a>
    </li>

    <!-- Proyek -->
    <li>
        <a href="{{ route('admin-pusat.project.index') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition
            {{ request()->routeIs('admin-pusat.project.*')
                ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-5m-4 0V5a2 2 0 1 1 4 0v1m-4 0a2 2 0 1 0 4 0m-5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 0 0-2.83 2M15 11h3m-3 4h2" />
            </svg>
            <span class="ms-3 text-sm">Proyek</span>
        </a>
    </li>

    <!-- Dropdown: Pelaporan -->
    <li x-data="{ open: {{ request()->routeIs('admin-pusat.rtkn*', 'admin-pusat.rtkd*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
            class="flex items-center cursor-pointer w-full px-3 py-2.5 rounded-lg transition
            {{ request()->routeIs('admin-pusat.rtkn*', 'admin-pusat.rtkd*')
                ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
            </svg>
            <span class="flex-1 ms-3 text-left text-sm">Pelaporan</span>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <ul x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pl-4">
            <li>
                <a href="{{ route('admin-pusat.rtkn.index') }}"
                    class="flex items-center pl-7 px-3 py-2 rounded-md transition text-xs
                    {{ request()->routeIs('admin-pusat.rtkn.index*')
                        ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                        : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
                    Rekapitulasi Rencana Tenaga Kerja Nasional
                </a>
            </li>
            <li>
                <a href="{{ route('admin-pusat.rtkd.index') }}"
                    class="flex items-center pl-7 px-3 py-2 rounded-md transition text-xs
                    {{ request()->routeIs('admin-pusat.rtkd*')
                        ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                        : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
                    Rekapitulasi Rencana Tenaga Kerja Provinsi
                </a>
            </li>
        </ul>
    </li>

    <!-- Rekapitulasi SDM -->
    <li>
        <a href="{{ route('admin-pusat.rekapitulasi.index') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition
            {{ request()->routeIs('admin-pusat.rekapitulasi*')
                ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
            </svg>
            <span class="flex-1 ms-3 whitespace-nowrap text-sm">Rekapitulasi SDM</span>
        </a>
    </li>

    <!-- Dropdown: Pemanfaatan RTKD -->
    <li x-data="{ open: {{ request()->routeIs('admin-pusat.survey-periods.*', 'admin-pusat.hasil-pemanfaatan-rtkd.*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
            class="flex items-center cursor-pointer w-full px-3 py-2.5 rounded-lg transition
            {{ request()->routeIs('admin-pusat.survey-periods.*', 'admin-pusat.hasil-pemanfaatan-rtkd.*')
                ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span class="flex-1 ms-3 text-left text-sm">Pemanfaatan RTKD</span>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <ul x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pl-4">
            <li>
                <a href="{{ route('admin-pusat.survey-periods.index') }}"
                    class="flex items-center pl-7 px-3 py-2 rounded-md transition text-xs
                    {{ request()->routeIs('admin-pusat.survey-periods.*')
                        ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                        : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
                    Periode Survei
                </a>
            </li>
            <li>
                <a href="{{ route('admin-pusat.hasil-pemanfaatan-rtkd.index') }}"
                    class="flex items-center pl-7 px-3 py-2 rounded-md transition text-xs
                    {{ request()->routeIs('admin-pusat.hasil-pemanfaatan-rtkd.*')
                        ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                        : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
                    Hasil Kuesioner
                </a>
            </li>
        </ul>
    </li>

    <!-- Management Course (Single Menu) -->
    <li>
        <a href="{{ route('admin-pusat.management-course.courses.index') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition
            {{ request()->routeIs('admin-pusat.management-course*')
                ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="5" width="18" height="14" rx="2" />
                <path d="M7 9h4" />
                <path d="M7 12h2" />
                <circle cx="16" cy="12" r="2.5" />
            </svg>
            <span class="ms-3 text-sm">Management Course</span>
        </a>
    </li>

    <!-- Dropdown: Perpustakaan -->
    <li x-data="{ open: {{ request()->routeIs('admin-pusat.library-categories.*', 'admin-pusat.libraries.*') ? 'true' : 'false' }} }">
        <button @click="open = !open"
            class="flex items-center cursor-pointer w-full px-3 py-2.5 rounded-lg transition
            {{ request()->routeIs('admin-pusat.library-categories.*', 'admin-pusat.libraries.*')
                ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13c-2.819-.831-4.715-1-7-1v14c2.285 0 4.181.169 7 1m0-14c2.819-.831 4.715-1 7-1v14c-2.285 0-4.181.169-7 1" />
            </svg>
            <span class="flex-1 ms-3 text-left text-sm">Perpustakaan</span>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
        </button>

        <ul x-show="open" x-collapse x-cloak class="mt-1 space-y-1 pl-4">
            <li>
                <a href="{{ route('admin-pusat.library-categories.index') }}"
                    class="flex items-center pl-7 px-3 py-2 rounded-md transition text-xs
                    {{ request()->routeIs('admin-pusat.library-categories.*')
                        ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                        : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
                    Kategori Perpustakaan
                </a>
            </li>
            <li>
                <a href="{{ route('admin-pusat.libraries.index') }}"
                    class="flex items-center pl-7 px-3 py-2 rounded-md transition text-xs
                    {{ request()->routeIs('admin-pusat.libraries.*')
                        ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                        : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
                    Materi Perpustakaan
                </a>
            </li>
        </ul>
    </li>

    <!-- Sertifikat -->
    <li>
        <a href="{{ route('admin-pusat.certificates.index') }}"
            class="flex items-center px-3 py-2.5 rounded-lg transition
            {{ request()->routeIs('admin-pusat.certificates.*')
                ? 'text-[#13416B] bg-[#13416B]/20 font-semibold'
                : 'text-gray-600 hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <span class="ms-3 text-sm">Sertifikat</span>
        </a>
    </li>
</ul>