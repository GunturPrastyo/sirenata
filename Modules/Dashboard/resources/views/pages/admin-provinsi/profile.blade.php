<x-dashboard::layouts.dashboard title="Profile">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="../index.html"
                        class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Profil</span>
                    </div>
                </li>
            </ol>
        </nav>

        <x-validation-errors class="mb-2" />

        <form action="{{ route('admin-province.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="sm:bg-white sm:rounded-2xl sm:shadow-lg overflow-hidden sm:border sm:border-gray-100">
                <div class="relative h-48">
                    <img src="{{ asset('images/login.jpg') }}" alt="Profile Banner" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="absolute -bottom-20 left-1/2 transform -translate-x-1/2">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name={{ $user->profile?->full_name ?? $user->name }}&background=6366f1&color=fff&size=160"
                                alt="Profile" class="w-40 h-40 rounded-full border-4 border-white shadow-xl">
                            <div
                                class="absolute bottom-3 right-3 w-7 h-7 bg-green-500 border-2 border-white rounded-full">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-24 px-6 md:px-12 pb-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="nama"
                                class="block text-sm font-semibold text-gray-700 mb-2 after:ml-0.5 after:text-red-500 after:content-['*']">
                                Nama Lengkap
                            </label>
                            <input type="text" id="nama" name="full_name"
                                value="{{ old('full_name', $user->profile?->full_name) }}"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-700 ">
                            <x-input-error field="full_name" />
                        </div>

                        <!-- NIK -->
                        <div>
                            <label for="nik" class="block text-sm font-semibold text-gray-700 mb-2">
                                NIK
                            </label>
                            <input type="text" id="nik" value="{{ $user->profile?->nik }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-700 cursor-not-allowed">
                            <x-input-error field="nik" />
                        </div>
                    </div>

                    <!-- Row 2: Email, Phone, Instansi -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" id="email" value="{{ $user->email }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-700 cursor-not-allowed">

                            <x-input-error field="email" />
                        </div>

                        <div>
                            <label for="phone"
                                class="block text-sm font-semibold text-gray-700 mb-2 after:ml-0.5 after:text-red-500 after:content-['*']">
                                Nomor Handphone
                            </label>
                            <input type="tel" id="phone" name="phone" inputmode="numeric" pattern="[0-9+]*"
                                placeholder="08xxxx atau +628xxxx" value="{{ old('phone', $user->profile?->phone) }}"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-700">
                            <x-input-error field="phone" />
                        </div>

                        <div>
                            <label for="gender"
                                class="block text-sm font-semibold text-gray-700 mb-2 after:ml-0.5 after:text-red-500 after:content-['*']">
                                Gender
                            </label>
                            <select id="gender" name="gender"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-700">
                                <option value="" disabled selected>Pilih Gender</option>
                                <option value="male"
                                    {{ old('gender', $user->profile?->gender) == 'male' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="female"
                                    {{ old('gender', $user->profile?->gender) == 'female' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                            <x-input-error field="gender" />
                        </div>
                        <div>
                            <label for="instansi"
                                class="block text-sm font-semibold text-gray-700 mb-2 after:ml-0.5 after:text-red-500 after:content-['*'] ">
                                Kementerian/Lembaga
                            </label>
                            <input type="text" id="instansi" name="instansi"
                                value="{{ old('instansi', $user->profile?->instansi) }}"
                                placeholder="Kementerian Ketenagakerjaan RI"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-700">
                            <x-input-error field="instansi" />
                        </div>

                        <div>
                            <label for="unit_kerja"
                                class="block text-sm font-semibold text-gray-700 mb-2 after:ml-0.5 after:text-red-500 after:content-['*'] ">
                                Unit Kerja
                            </label>
                            <input type="text" id="unit_kerja" name="unit_kerja"
                                value="{{ old('unit_kerja', $user->profile?->unit_kerja) }}" placeholder="SDM"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-700">
                            <x-input-error field="unit_kerja" />
                        </div>
                        <div>
                            <label for="province_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                Provinsi
                            </label>
                            <input type="text" id="province_code" readonly
                                value="{{ $user->scopeArea?->province?->name }}" placeholder="Provinsi"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-700">
                        </div>
                        <div>
                            <label for="province_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kab/Kota
                            </label>
                            <input type="text" id="province_code" readonly
                                value="{{ $user->scopeArea?->regency?->name }}" placeholder="Kab/Kota"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-700">
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Row 3: Password Section -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Ubah Password</h2>
                        <p class="text-sm text-gray-600 mb-6">Kosongkan jika tidak ingin mengubah password</p>

                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Lama
                            </label>
                            <div class="relative">
                                <input type="password" id="current_password" name="current_password"
                                    placeholder="Masukkan password lama"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all pr-12 text-gray-800">

                                <button type="button" onclick="togglePassword('current_password', event)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                <x-input-error field="current_password" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password Baru
                                </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password"
                                        placeholder="Masukkan password baru" oninput="validatePassword()"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all pr-12 text-gray-800">
                                    <button type="button" onclick="togglePassword('password', event)"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <x-input-error field="password" />
                                </div>

                                <!-- Password Strength Meter -->
                                <div class="mt-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-medium text-gray-700">Kekuatan Password:</span>
                                        <span id="strengthLabel" class="text-xs font-semibold text-gray-500">-</span>
                                    </div>
                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="strengthMeter"
                                            class="h-full transition-all duration-300 rounded-full bg-gray-300"
                                            style="width: 0%"></div>
                                    </div>
                                </div>

                                <!-- Password Requirements -->
                                <div class="mt-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-xs font-semibold text-gray-700 mb-3">Persyaratan Password:</h4>
                                    <div class="space-y-2">
                                        <div id="req-length" class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="10" opacity="0.3" />
                                            </svg>
                                            <span>Minimal 8 karakter</span>
                                        </div>
                                        <div id="req-lowercase" class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="10" opacity="0.3" />
                                            </svg>
                                            <span>Minimal 1 huruf kecil (a-z)</span>
                                        </div>
                                        <div id="req-uppercase" class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="10" opacity="0.3" />
                                            </svg>
                                            <span>Minimal 1 huruf besar (A-Z)</span>
                                        </div>
                                        <div id="req-number" class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="10" opacity="0.3" />
                                            </svg>
                                            <span>Minimal 1 angka (0-9)</span>
                                        </div>
                                        <div id="req-symbol" class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="10" opacity="0.3" />
                                            </svg>
                                            <span>Minimal 1 karakter khusus (contoh: !@#$%^&*)</span>
                                        </div>
                                        <div id="req-match" class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="10" opacity="0.3" />
                                            </svg>
                                            <span>Password dan konfirmasi cocok</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="repassword" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Re-Password
                                </label>
                                <div class="relative">
                                    <input type="password" id="repassword" name="password_confirmation"
                                        placeholder="Masukkan ulang password" oninput="validatePasswordMatch()"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all pr-12 text-gray-800">
                                    <button type="button" onclick="togglePassword('repassword', event)"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    <x-input-error field="password_confirmation" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-10 flex justify-end">
                        <button type="submit"
                            class="px-8 py-3.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        {{-- script password --}}
        @vite('Modules/Dashboard/resources/assets/js/app.js')
    @endpush

</x-dashboard::layouts.dashboard>
