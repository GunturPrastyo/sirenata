<li>
    <a href="{{ route('user.dashboard') }}"
        class="flex items-center px-2 py-1.5 {{ request()->routeIs('user.dashboard') ? 'text-indigo-600 bg-purple-100' : 'text-gray-500 hover:bg-purple-100 hover:text-indigo-600' }} rounded-lg group transition-colors">
        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('user.dashboard') ? 'text-indigo-600' : 'group-hover:text-indigo-600' }}"
            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
        </svg>
        <span class="ms-3">Dashboard</span>
    </a>
</li>

<li>
    <a href="{{ route('user.course.my-course') }}"
        class="flex items-center px-2 py-1.5 {{ request()->routeIs('user.course.my-course.*') ? 'text-indigo-600 bg-purple-100' : 'text-gray-500 hover:bg-purple-100 hover:text-indigo-600' }} rounded-lg group transition-colors">
        <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.course.my-course.*') ? 'text-indigo-600' : 'group-hover:text-indigo-600' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h12M6 4v16M6 20h12M6 20H5m13 0h1m-1 0V4m0 0h1M6 4H5M9 8h1v1H9V8Zm5 0h1v1h-1V8Zm-5 4h1v1H9v-1Zm5 0h1v1h-1v-1Zm-3 4h2a1 1 0 0 1 1 1v4h-4v-4a1 1 0 0 1 1-1Z" />
        </svg>
        <span class="ms-3">Kursus Saya</span>
    </a>
</li>

<li>
    <a href="#"
        class="flex items-center px-2 py-1.5 text-gray-500 rounded-lg hover:bg-purple-100 hover:text-indigo-600 transition-colors group">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-indigo-600" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z" />
        </svg>
        <span class="flex-1 ms-3 whitespace-nowrap">Katalog</span>
    </a>
</li>
<li>
    <a href="{{ route('user.library.index') }}"
        class="flex items-center px-2 py-1.5 {{ request()->routeIs('user.library.index') ? 'text-indigo-600 bg-purple-100' : 'text-gray-500 hover:bg-purple-100 hover:text-indigo-600' }} rounded-lg transition-colors group">
        <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.library.index') ? 'text-indigo-600' : 'group-hover:text-indigo-600' }}"
            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.03v13m0-13c-2.819-.831-4.715-1.076-8.029-1.023A.99.99 0 0 0 3 6v11c0 .563.466 1.014 1.03 1.007 3.122-.043 5.018.212 7.97 1.023m0-13c2.819-.831 4.715-1.076 8.029-1.023A.99.99 0 0 1 21 6v11c0 .563-.466 1.014-1.03 1.007-3.122-.043-5.018.212-7.97 1.023" />
        </svg>
        <span class="flex-1 ms-3 whitespace-nowrap">Perpustakaan</span>
    </a>
</li>
<li>
    <button x-data="{ open: false }" @click="open = !open"
        class="flex items-center justify-between w-full px-2 py-1.5 text-gray-500 rounded-lg hover:bg-purple-100 hover:text-indigo-600 transition-colors group">
        <div class="flex items-center">
            <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-indigo-600" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4m8-4h.01M12 16h.01M16 12h.01M8 12h.01" />
            </svg>
            <span class="flex-1 ms-3 whitespace-nowrap">Perhitungan</span>
        </div>
        <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor"
            viewBox="0 0 10 6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
        </svg>
    </button>
    <ul x-show="open" class="py-2 space-y-2" x-cloak>
        <li>
            <a href="/rtk-makro/index.html" target="_blank"
                class="flex items-center w-full px-2 py-1.5 ps-10 text-gray-500 rounded-lg hover:bg-purple-100 hover:text-indigo-600 transition-colors">
                Kalkulator RTK
            </a>
        </li>
        <li>
            <a href="#"
                class="flex items-center w-full px-2 py-1.5 ps-10 text-gray-500 rounded-lg hover:bg-purple-100 hover:text-indigo-600 transition-colors">
                Draft RTK
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="{{ route('user.help') }}"
        class="flex items-center px-2 py-1.5 {{ request()->routeIs('user.help') ? 'text-indigo-600 bg-purple-100' : 'text-gray-500 hover:bg-purple-100 hover:text-indigo-600' }} rounded-lg group transition-colors">
        <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.help') ? 'text-indigo-600' : 'group-hover:text-indigo-600' }}"
            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span class="flex-1 ms-3 whitespace-nowrap">Bantuan</span>
    </a>
</li>
