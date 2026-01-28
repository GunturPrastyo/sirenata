<x-dashboard::layouts.portal title="Portal Dashboard">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">

        <!-- Header -->
        <div class="text-center mb-12 mt-20">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Portal Dashboard SIRENATA</h1>
            <p class="text-xl text-gray-600">Pilih dashboard sesuai dengan role Anda</p>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 ">
            <!-- Superadmin Dashboard -->
            @role('super-admin')
                <x-dashboard::accesscard title="Superadmin" description="Administrasi dan konfigurasi sistem"
                    url="{{ route('super-admin.dashboard') }}" variant="red">
                    <div
                        class="w-20 h-20 bg-red-100 rounded-2xl  flex items-center justify-center mb-6 group-hover:bg-red-500 transition-all">
                        <svg class="w-10 h-10 text-red-600 group-hover:text-white transition-all" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </x-dashboard::accesscard>
            @endrole
            <!-- Admin Pusat Dashboard -->
            @role('admin-pusat')
                <x-dashboard::accesscard title="Admin Pusat" description="Manajemen tingkat pusat" url="#"
                    variant="purple">
                    <div
                        class="w-20 h-20 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-500 transition-all">
                        <svg class="w-10 h-10 text-purple-600 group-hover:text-white transition-all" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </x-dashboard::accesscard>
            @endrole

            <!-- Admin Provinsi Dashboard -->
            @role('admin-provinsi')
                <x-dashboard::accesscard title="Admin Provinsi" description="Manajemen tingkat provinsi" url="#"
                    variant="emerald">
                    <div
                        class="w-20 h-20 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-500 transition-all">
                        <svg class="w-10 h-10 text-emerald-600 group-hover:text-white transition-all" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </x-dashboard::accesscard>
            @endrole

            <!-- Admin Kab/Kota Dashboard -->
            @role('admin-kabupaten-kota')
                <x-dashboard::accesscard title="Admin Kab/Kota" description="Manajemen tingkat kabupaten/kota"
                    url="#" variant="orange">
                    <div
                        class="w-20 h-20 bg-orange-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-500 transition-all">
                        <svg class="w-10 h-10 text-orange-600 group-hover:text-white transition-all" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                        </svg>
                    </div>
                </x-dashboard::accesscard>
            @endrole

            <!-- User/Peserta Dashboard -->
            @role('user-peserta')
                <x-dashboard::accesscard title="User/Peserta" description="Dashboard pembelajaran peserta" url="#"
                    variant="indigo">
                    <div
                        class="w-20 h-20 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-500 transition-all">
                        <svg class="w-10 h-10 text-indigo-600 group-hover:text-white transition-all" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </x-dashboard::accesscard>
            @endrole
        </div>

        <!-- Info Section -->
        <div class="mt-16 text-center">
            <div class="bg-white rounded-2xl p-8 max-w-2xl mx-auto shadow-md border-2 border-gray-100">
                <svg class="w-12 h-12 text-indigo-600 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Informasi</h3>
                <p class="text-gray-600">
                    Halaman ini adalah portal akses ke semua dashboard SIRENATA. Pilih dashboard sesuai dengan role dan
                    hak akses Anda.
                    Jika Anda belum memiliki akun, silakan <a href="auth/register.html"
                        class="text-indigo-600 hover:text-indigo-800 font-semibold">daftar terlebih dahulu</a>.
                </p>
            </div>
        </div>
    </div>
</x-dashboard::layouts.portal>
