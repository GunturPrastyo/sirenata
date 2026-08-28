<ul class="flex flex-row lg:flex-col gap-1 sm:gap-2 lg:gap-0 lg:space-y-1.5 px-2 sm:px-4 lg:px-4 font-medium w-full h-full lg:h-auto items-center lg:items-stretch justify-around lg:justify-start">
    
    <!-- Dashboard -->
    <li class="flex-1 lg:flex-none">
        <a href="{{ route('user.dashboard') }}"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-3 py-1.5 sm:py-2 lg:py-2.5 rounded-xl lg:rounded-lg transition-colors group
            {{ request()->routeIs('user.dashboard')
                ? 'text-[#13416B] bg-[#13416B]/10 lg:bg-[#13416B]/20 font-bold lg:font-semibold'
                : 'text-slate-400 lg:text-slate-600 hover:bg-[#13416B]/5 lg:hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-75 {{ request()->routeIs('user.dashboard') ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1">Dashboard</span>
        </a>
    </li>

    <!-- Kursus Saya -->
    <li class="flex-1 lg:flex-none">
        <a href="{{ route('user.course.my-course') }}"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-3 py-1.5 sm:py-2 lg:py-2.5 rounded-xl lg:rounded-lg transition-colors group
            {{ request()->routeIs('user.course.my-course*')
                ? 'text-[#13416B] bg-[#13416B]/10 lg:bg-[#13416B]/20 font-bold lg:font-semibold'
                : 'text-slate-400 lg:text-slate-600 hover:bg-[#13416B]/5 lg:hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-75 {{ request()->routeIs('user.course.my-course*') ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h12M6 4v16M6 20h12M6 20H5m13 0h1m-1 0V4m0 0h1M6 4H5M9 8h1v1H9V8Zm5 0h1v1h-1V8Zm-5 4h1v1H9v-1Zm5 0h1v1h-1v-1Zm-3 4h2a1 1 0 0 1 1 1v4h-4v-4a1 1 0 0 1 1-1Z" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1">Kursus Saya</span>
        </a>
    </li>

    <!-- Katalog -->
    <li class="flex-1 lg:flex-none">
        <a href="#"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-3 py-1.5 sm:py-2 lg:py-2.5 rounded-xl lg:rounded-lg transition-colors group text-slate-400 lg:text-slate-600 hover:bg-[#13416B]/5 lg:hover:bg-[#13416B]/10 hover:text-[#13416B]">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-75 group-hover:text-[#13416B]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1">Katalog</span>
        </a>
    </li>

    <!-- Perpustakaan -->
    <li class="flex-1 lg:flex-none">
        <a href="{{ route('user.library.index') }}"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-3 py-1.5 sm:py-2 lg:py-2.5 rounded-xl lg:rounded-lg transition-colors group
            {{ request()->routeIs('user.library.index')
                ? 'text-[#13416B] bg-[#13416B]/10 lg:bg-[#13416B]/20 font-bold lg:font-semibold'
                : 'text-slate-400 lg:text-slate-600 hover:bg-[#13416B]/5 lg:hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-75 {{ request()->routeIs('user.library.index') ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.03v13m0-13c-2.819-.831-4.715-1.076-8.029-1.023A.99.99 0 0 0 3 6v11c0 .563.466 1.014 1.03 1.007 3.122-.043 5.018.212 7.97 1.023m0-13c2.819-.831 4.715-1.076 8.029-1.023A.99.99 0 0 1 21 6v11c0 .563-.466 1.014-1.03 1.007-3.122-.043-5.018.212-7.97 1.023" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1">Perpustakaan</span>
        </a>
    </li>

    <!-- Kalkulator RTK -->
    <li class="flex-1 lg:flex-none">
        <a href="{{ route('user.kalkulator.sandbox') }}" target="_blank"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-3 py-1.5 sm:py-2 lg:py-2.5 rounded-xl lg:rounded-lg transition-colors group text-slate-400 lg:text-slate-600 hover:bg-[#13416B]/5 lg:hover:bg-[#13416B]/10 hover:text-[#13416B]">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-75 group-hover:text-[#13416B]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4m8-4h.01M12 16h.01M16 12h.01M8 12h.01" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1 flex justify-center lg:justify-start items-center gap-1">Kalkulator RTK</span>
        </a>
    </li>

    <!-- Tim Kerja (Conditional) -->
    @php
        $userProjects = \Modules\Project\Models\Project::where('team_leader', auth()->id())
            ->orWhereJsonContains('team_members', auth()->id())
            ->exists();
    @endphp
    @if($userProjects)
    <li class="flex-1 lg:flex-none">
        <a href="{{ route('user.tim-kerja.index') }}"
            class="flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-3 py-1.5 sm:py-2 lg:py-2.5 rounded-xl lg:rounded-lg transition-colors group
            {{ request()->routeIs('user.tim-kerja.*')
                ? 'text-[#13416B] bg-[#13416B]/10 lg:bg-[#13416B]/20 font-bold lg:font-semibold'
                : 'text-slate-400 lg:text-slate-600 hover:bg-[#13416B]/5 lg:hover:bg-[#13416B]/10 hover:text-[#13416B]' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-75 {{ request()->routeIs('user.tim-kerja.*') ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1">Tim Kerja</span>
        </a>
    </li>
    @endif
</ul>