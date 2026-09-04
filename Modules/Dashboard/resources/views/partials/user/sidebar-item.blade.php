<ul class="flex flex-row lg:flex-col gap-1 sm:gap-2 lg:gap-1.5 lg:space-y-1 px-2 sm:px-4 lg:px-4 font-medium w-full h-full lg:h-auto items-center lg:items-stretch justify-around lg:justify-start">
    
    <!-- Dashboard -->
    @php $isDashboard = request()->routeIs('user.dashboard'); @endphp
    <li class="flex-1 lg:flex-none">
        <a href="{{ route('user.dashboard') }}"
            class="relative flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-4 py-1.5 sm:py-2 lg:py-3 rounded-xl transition-all duration-300 group {{ $isDashboard ? 'text-[#13416B] font-bold lg:bg-[#13416B]/5' : 'text-slate-400 lg:text-slate-500 hover:text-[#13416B] lg:hover:bg-slate-50' }}">
            
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-200 {{ $isDashboard ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}"
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="{{ $isDashboard ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="{{ $isDashboard ? '1' : '2' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1">Dashboard</span>
        </a>
    </li>

    <!-- Kursus Saya -->
    @php $isKursus = request()->routeIs('user.course.my-course*'); @endphp
    <li class="flex-1 lg:flex-none">
        <a href="{{ route('user.course.my-course') }}"
            class="relative flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-4 py-1.5 sm:py-2 lg:py-3 rounded-xl transition-all duration-300 group {{ $isKursus ? 'text-[#13416B] font-bold lg:bg-[#13416B]/5' : 'text-slate-400 lg:text-slate-500 hover:text-[#13416B] lg:hover:bg-slate-50' }}">
            
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-200 {{ $isKursus ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}" 
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="{{ $isKursus ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="{{ $isKursus ? '0.5' : '2' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 4h12M6 4v16M6 20h12M6 20H5m13 0h1m-1 0V4m0 0h1M6 4H5M9 8h1v1H9V8Zm5 0h1v1h-1V8Zm-5 4h1v1H9v-1Zm5 0h1v1h-1v-1Zm-3 4h2a1 1 0 0 1 1 1v4h-4v-4a1 1 0 0 1 1-1Z" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1">Kursus Saya</span>
        </a>
    </li>

    <!-- Katalog -->
    @php $isKatalog = request()->routeIs('user.course.catalog*'); @endphp
    <li class="flex-1 lg:flex-none">
        <a href="#"
            class="relative flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-4 py-1.5 sm:py-2 lg:py-3 rounded-xl transition-all duration-300 group {{ $isKatalog ? 'text-[#13416B] font-bold lg:bg-[#13416B]/5' : 'text-slate-400 lg:text-slate-500 hover:text-[#13416B] lg:hover:bg-slate-50' }}">
            
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-200 {{ $isKatalog ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}" 
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="{{ $isKatalog ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="{{ $isKatalog ? '0.5' : '2' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1">Katalog</span>
        </a>
    </li>

    <!-- Perpustakaan -->
    @php $isLibrary = request()->routeIs('user.library.index'); @endphp
    <li class="flex-1 lg:flex-none">
        <a href="{{ route('user.library.index') }}"
            class="relative flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-4 py-1.5 sm:py-2 lg:py-3 rounded-xl transition-all duration-300 group {{ $isLibrary ? 'text-[#13416B] font-bold lg:bg-[#13416B]/5' : 'text-slate-400 lg:text-slate-500 hover:text-[#13416B] lg:hover:bg-slate-50' }}">
            
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-200 {{ $isLibrary ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}" 
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="{{ $isLibrary ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="{{ $isLibrary ? '0.5' : '2' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.03v13m0-13c-2.819-.831-4.715-1.076-8.029-1.023A.99.99 0 0 0 3 6v11c0 .563.466 1.014 1.03 1.007 3.122-.043 5.018.212 7.97 1.023m0-13c2.819-.831 4.715-1.076 8.029-1.023A.99.99 0 0 1 21 6v11c0 .563-.466 1.014-1.03 1.007-3.122-.043-5.018.212-7.97 1.023" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1">Perpustakaan</span>
        </a>
    </li>

    <!-- Kalkulator RTK -->
  @php $isKalkulator = request()->routeIs('user.kalkulator.sandbox'); @endphp
    <li class="flex-1 lg:flex-none">
        <a href="{{ route('user.kalkulator.sandbox') }}" target="_blank"
            class="relative flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-4 py-1.5 sm:py-2 lg:py-3 rounded-xl transition-all duration-300 group {{ $isKalkulator ? 'text-[#13416B] font-bold lg:bg-[#13416B]/5' : 'text-slate-400 lg:text-slate-500 hover:text-[#13416B] lg:hover:bg-slate-50' }}">
            
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-200 {{ $isKalkulator ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}" 
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="{{ $isKalkulator ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="{{ $isKalkulator ? '0.5' : '1.5' }}">
                <rect x="4" y="2" width="16" height="20" rx="2" ry="2" stroke-linecap="round" stroke-linejoin="round" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h8M16 14v4M8 10h.01M12 10h.01M16 10h.01M8 14h.01M12 14h.01M8 18h.01M12 18h.01" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1 flex justify-center lg:justify-start items-center gap-1">Penghitungan RTK</span>
        </a>
    </li>

    <!-- Tim Kerja (Conditional) -->
    @php
        $userProjects = \Modules\Project\Models\Project::where('team_leader', auth()->id())
            ->orWhereJsonContains('team_members', auth()->id())
            ->exists();
        $isTimKerja = request()->routeIs('user.tim-kerja.*');
    @endphp
    @if($userProjects)
    <li class="flex-1 lg:flex-none">
        <a href="{{ route('user.tim-kerja.index') }}"
            class="relative flex flex-col lg:flex-row items-center justify-center lg:justify-start px-1 sm:px-2 lg:px-4 py-1.5 sm:py-2 lg:py-3 rounded-xl transition-all duration-300 group {{ $isTimKerja ? 'text-[#13416B] font-bold lg:bg-[#13416B]/5' : 'text-slate-400 lg:text-slate-500 hover:text-[#13416B] lg:hover:bg-slate-50' }}">
            
            <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-5 lg:h-5 shrink-0 transition duration-200 {{ $isTimKerja ? 'text-[#13416B]' : 'group-hover:text-[#13416B]' }}" 
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                fill="{{ $isTimKerja ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="{{ $isTimKerja ? '0.5' : '2' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span class="text-[9px] sm:text-[11px] lg:text-sm mt-1 sm:mt-1.5 lg:mt-0 lg:ms-3 text-center lg:text-left line-clamp-1">Tim Kerja</span>
        </a>
    </li>
    @endif
</ul>