<li>
    <a href="{{ route('user.dashboard') }}"
        class="flex items-center px-2 py-1.5 {{ request()->routeIs('user.dashboard') ? 'text-[#13416B] bg-[#13416B]/30' : 'text-gray-500 hover:bg-[#13416B]/30 hover:text-[#13416B]' }} rounded-lg group transition-colors">
        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('user.dashboard') ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}"
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
        class="flex items-center px-2 py-1.5 {{ request()->routeIs('user.course.my-course*') ? 'text-[#13416B] bg-[#13416B]/30' : 'text-gray-500 hover:bg-[#13416B]/30 hover:text-[#13416B]' }} rounded-lg group transition-colors">
        <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.course.my-course*') ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h12M6 4v16M6 20h12M6 20H5m13 0h1m-1 0V4m0 0h1M6 4H5M9 8h1v1H9V8Zm5 0h1v1h-1V8Zm-5 4h1v1H9v-1Zm5 0h1v1h-1v-1Zm-3 4h2a1 1 0 0 1 1 1v4h-4v-4a1 1 0 0 1 1-1Z" />
        </svg>
        <span class="ms-3">Kursus Saya</span>
    </a>
</li>

<li>
    <a href="#"
        class="flex items-center px-2 py-1.5 text-gray-500 rounded-lg hover:bg-[#13416B]/30 hover:text-[#13416B] transition-colors group">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#13416B]" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z" />
        </svg>
        <span class="flex-1 ms-3 whitespace-nowrap">Katalog</span>
    </a>
</li>
<li>
    <a href="{{ route('user.library.index') }}"
        class="flex items-center px-2 py-1.5 {{ request()->routeIs('user.library.index') ? 'text-[#13416B] bg-[#13416B]/30' : 'text-gray-500 hover:bg-[#13416B]/30 hover:text-[#13416B]' }} rounded-lg transition-colors group">
        <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.library.index') ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}"
            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.03v13m0-13c-2.819-.831-4.715-1.076-8.029-1.023A.99.99 0 0 0 3 6v11c0 .563.466 1.014 1.03 1.007 3.122-.043 5.018.212 7.97 1.023m0-13c2.819-.831 4.715-1.076 8.029-1.023A.99.99 0 0 1 21 6v11c0 .563-.466 1.014-1.03 1.007-3.122-.043-5.018.212-7.97 1.023" />
        </svg>
        <span class="flex-1 ms-3 whitespace-nowrap">Perpustakaan</span>
    </a>
</li>
<li>
    <a href="{{ route('user.kalkulator.sandbox') }}" target="_blank"
        class="flex items-center px-2 py-1.5 text-gray-500 rounded-lg hover:bg-[#13416B]/30 hover:text-[#13416B] transition-colors group">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-[#13416B]" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4v16m8-8H4m8-4h.01M12 16h.01M16 12h.01M8 12h.01" />
        </svg>
        <span class="flex-1 ms-3 whitespace-nowrap">Kalkulator RTK</span>
        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
        </svg>
    </a>
</li>
@php
    $userProjects = \Modules\Project\Models\Project::where('team_leader', auth()->id())
        ->orWhereJsonContains('team_members', auth()->id())
        ->exists();
@endphp
@if($userProjects)
<li>
    <a href="{{ route('user.tim-kerja.index') }}"
        class="flex items-center px-2 py-1.5 {{ request()->routeIs('user.tim-kerja.*') ? 'text-[#13416B] bg-[#13416B]/30' : 'text-gray-500 hover:bg-[#13416B]/30 hover:text-[#13416B]' }} rounded-lg group transition-colors">
        <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.tim-kerja.*') ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
        </svg>
        <span class="flex-1 ms-3 whitespace-nowrap">Tim Kerja</span>
    </a>
</li>
@endif
<li>
    <a href="{{ route('user.help') }}"
        class="flex items-center px-2 py-1.5 {{ request()->routeIs('user.help') ? 'text-[#13416B] bg-[#13416B]/30' : 'text-gray-500 hover:bg-[#13416B]/30 hover:text-[#13416B]' }} rounded-lg group transition-colors">
        <svg class="shrink-0 w-5 h-5 transition duration-75 {{ request()->routeIs('user.help') ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}"
            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span class="flex-1 ms-3 whitespace-nowrap">Bantuan</span>
    </a>
</li>
