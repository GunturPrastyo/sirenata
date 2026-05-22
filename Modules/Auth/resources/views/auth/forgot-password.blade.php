<x-layouts.app title="Lupa Kata Sandi">
    <section class="bg-gray-50 min-h-screen flex flex-col items-center justify-center py-12 px-6 font-sans">
        <div class="w-full max-w-md">
            <!-- Logo & Brand -->
            <div class="flex flex-col items-center mb-8">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 text-3xl font-heading font-extrabold text-gray-900 tracking-tight">
                    <img class="h-10 w-auto" src="{{ asset('images/logo.png') }}" alt="logo">
                    <span>SIRENATA</span>
                </a>
            </div>

            <!-- Card Container -->
            <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-xl p-8 sm:p-10">
                <div class="space-y-6">
                    <div class="text-center">
                        <h1 class="text-xl font-heading font-bold text-gray-900 tracking-tight">
                            Lupa Kata Sandi?
                        </h1>
                        <p class="text-sm text-gray-500 mt-1.5 font-normal">
                            Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
                        </p>
                    </div>

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="flex p-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200" role="alert">
                            <i class="fas fa-check-circle text-base me-2 shrink-0"></i>
                            <div>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <!-- Error Validation -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <x-validation-errors />
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="block mb-2 text-sm font-semibold text-gray-700">
                                Alamat Email
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-blue-50 focus:border-blue-500 block w-full pl-10 pr-4 py-3 transition-all duration-200 outline-none"
                                    placeholder="nama@perusahaan.com" required>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] focus:ring-4 focus:outline-none focus:ring-blue-100 font-semibold rounded-xl text-sm px-5 py-3 text-center transition-all duration-150 shadow-md shadow-blue-100 cursor-pointer pt-3">
                            Kirim Tautan Atur Ulang
                        </button>
                    </form>

                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-500 font-normal">
                            Ingat kata sandi Anda? 
                            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                                Kembali ke Masuk
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
