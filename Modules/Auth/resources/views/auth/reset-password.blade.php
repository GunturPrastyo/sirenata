<x-layouts.app title="Atur Ulang Kata Sandi">
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
                            Atur Ulang Kata Sandi
                        </h1>
                        <p class="text-sm text-gray-500 mt-1.5 font-normal">
                            Masukkan kata sandi baru Anda untuk memperbarui akun Anda.
                        </p>
                    </div>

                    <!-- Error Validation -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <x-validation-errors />
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div>
                            <label for="email" class="block mb-2 text-sm font-semibold text-gray-700">
                                Alamat Email
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" id="email" value="{{ old('email', $email) }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-blue-50 focus:border-blue-500 block w-full pl-10 pr-4 py-3 transition-all duration-200 outline-none"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block mb-2 text-sm font-semibold text-gray-700">
                                Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <i class="fas fa-lock text-sm"></i>
                                </span>
                                <input type="password" name="password" id="password"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-blue-50 focus:border-blue-500 block w-full pl-10 pr-10 py-3 transition-all duration-200 outline-none"
                                    placeholder="••••••••" required>
                                <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-gray-700">
                                Konfirmasi Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                    <i class="fas fa-lock text-sm"></i>
                                </span>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-4 focus:ring-blue-50 focus:border-blue-500 block w-full pl-10 pr-10 py-3 transition-all duration-200 outline-none"
                                    placeholder="••••••••" required>
                                <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 active:scale-[0.99] focus:ring-4 focus:outline-none focus:ring-blue-100 font-semibold rounded-xl text-sm px-5 py-3 text-center transition-all duration-150 shadow-md shadow-blue-100 cursor-pointer pt-3">
                            Atur Ulang Kata Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash text-sm';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye text-sm';
            }
        }
    </script>
</x-layouts.app>
