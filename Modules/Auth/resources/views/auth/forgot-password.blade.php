<x-layouts.app title="Forgot Password">
    <section class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto h-screen lg:py-0">
            <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900">
                <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg"
                    alt="logo">
                Flowbite
            </a>

            <div class="w-full bg-white rounded-lg shadow-2xl md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                        Lupa Password
                    </h1>
                    <p class="text-sm text-gray-500">
                        Masukkan email Anda dan kami akan mengirimkan melalui email.
                    </p>

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50">
                            {{ session('success') }}
                        </div>
                    @endif

                    <x-validation-errors class="mb-2" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4 md:space-y-6">
                        @csrf
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">
                                Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                placeholder="name@company.com" required>
                        </div>

                        <button type="submit"
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Kirim Link Reset Password
                        </button>

                        <p class="text-sm font-light text-gray-500">
                            Ingat password Anda?
                            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:underline">
                                Kembali ke Login
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
