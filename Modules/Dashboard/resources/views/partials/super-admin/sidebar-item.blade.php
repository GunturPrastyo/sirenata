<li>
    <a href="{{ route('super-admin.dashboard') }}" class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('super-admin.dashboard')
    ? 'text-indigo-600 bg-purple-100'
    : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-indigo-600" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
        </svg>
        <span class="ms-3">Dashboard</span>
    </a>
</li>

<li>
    <a href="{{ route('super-admin.user-management.index') }}" class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('super-admin.user-management.index')
    ? 'text-indigo-600 bg-purple-100'
    : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}" hover:bg-purple-100 hover:text-indigo-600
        transition">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-indigo-600" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
        </svg>
        <span class="ms-3">Manajemen User</span>
    </a>
</li>

<li>
    <a href="{{ route('super-admin.help') }}" class="flex items-center px-2 py-1.5 rounded-md transition
        {{ request()->routeIs('super-admin.help')
    ? 'text-indigo-600 bg-purple-100'
    : 'text-gray-600 hover:bg-purple-100 hover:text-indigo-600' }}">
        <svg class="shrink-0 w-5 h-5 transition duration-75 group-hover:text-indigo-600" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
        </svg>
        <span class="ms-3">Bantuan / FAQ</span>
    </a>
</li>