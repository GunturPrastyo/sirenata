<div>
    <div class="my-3">
        <x-validation-errors />
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-6 mb-6 card-hover">
        <form wire:submit="store">
            <!-- Informasi Pribadi -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-indigo-100 text-indigo-600">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Informasi Pribadi</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 form-grid">
                    <x-form.input name="name" label="Name" wire:model.lazy="name" required placeholder="Masukkan name" />
                    
                    <x-form.input name="full_name" label="Nama Lengkap" wire:model.lazy="full_name" required placeholder="Masukkan nama lengkap" helper="Contoh: Sarah Putri" />

                    <x-form.input type="email" name="email" label="Email" wire:model.lazy="email" required placeholder="contoh@kemnaker.go.id" helper="Harus menggunakan domain kemnaker.go.id" />

                    <x-form.input type="tel" name="phone" label="Nomor Telepon" wire:model.lazy="phone" required placeholder="081234567890" helper="Format: 081234567890" inputmode="numeric" />

                    <x-form.input name="instansi" label="Instansi" wire:model.lazy="instansi" required placeholder="Contoh: Staff Administrasi" />

                    <x-form.select name="province" label="Provinsi" wire:model.lazy="province">
                        <option value="">Semua Status</option>
                        @foreach ($this->provinces as $province)
                            <option value="{{ $province->code }}">{{ $province->name }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select name="regency" label="Kab/Kota" wire:model.lazy="regency">
                        <option value="">Semua Status</option>
                        @foreach ($this->regencies as $regency)
                            <option value="{{ $regency->code }}">{{ $regency->name }}</option>
                        @endforeach
                    </x-form.select>
                </div>
            </div>

            <!-- Informasi Akun -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                        <i class="fas fa-key"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Informasi Akun</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 form-grid">
                    <div wire:ignore>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <input id="password" type="password" wire:model.lazy="password"
                                class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                                placeholder="Masukkan password">

                            <button type="button" class="absolute right-3 top-3 text-gray-500"
                                onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="mt-2">
                            <div class="flex justify-between mb-1">
                                <span class="text-xs text-gray-500">Kekuatan password:</span>
                                <span id="password-strength-text" class="text-xs font-medium">-</span>
                            </div>

                            <div class="h-1 w-full bg-gray-200 rounded-full overflow-hidden">
                                <div id="password-strength-bar"
                                    class="h-full rounded-full transition-all duration-300" style="width:0%">
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-2">
                            Minimal 8 karakter, kombinasi huruf, angka & simbol
                        </p>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password"
                                wire:model.lazy="password_confirmation"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 form-input"
                                placeholder="Konfirmasi password">
                            <button type="button" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700"
                                onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password_confirmation')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div id="password-match" class="hidden mt-2">
                            <p class="text-xs text-red-500 flex items-center">
                                <i class="fas fa-times-circle mr-1"></i> Password tidak cocok
                            </p>
                        </div>
                    </div>
                </div>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                @error('password_confirmation')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pengaturan Role -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-purple-100 text-purple-600">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Role dan Izin Akses</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2  after:ml-0.5 after:text-red-500 after:content-['*'] ">
                            Role
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @forelse ($roles as $role)
                                <div class="relative">
                                    <input type="checkbox" id="role_{{ $role->uuid }}" wire:model="roleIds"
                                        value="{{ $role->uuid }}" class="hidden peer"
                                        :disabled="$wire.roleIds.length >= 2 && !$wire.roleIds.includes('{{ $role->uuid }}')">

                                    <label for="role_{{ $role->uuid }}"
                                        class="flex flex-col h-full p-4 border-2 border-slate-200 rounded-xl cursor-pointer
                                        hover:border-indigo-300
                                        peer-checked:border-indigo-500
                                        peer-checked:bg-indigo-50
                                        peer-disabled:opacity-50
                                        peer-disabled:cursor-not-allowed
                                        transition">
                                        <div class="flex items-center mb-2">
                                            <div class="p-2 rounded-lg bg-indigo-100 text-indigo-600 mr-3">
                                                <i class="fas fa-user-shield"></i>
                                            </div>

                                            <span class="font-medium text-slate-900">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        </div>

                                        <p class="text-sm text-slate-600 leading-relaxed">
                                            Akses sesuai role
                                            <span class="font-medium">{{ $role->name }}</span>
                                        </p>
                                    </label>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500 col-span-full">
                                    Role tidak tersedia
                                </p>
                            @endforelse
                        </div>
                        @error('roleId')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 action-buttons form-section">
                <x-button type="submit" id="btn-submit" icon="fas fa-save">
                    Simpan User
                </x-button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {

            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('confirm_password');

            if (passwordInput) {
                passwordInput.addEventListener('input', (e) => {
                    checkPasswordStrength(e.target.value);
                });
            }

            if (confirmInput) {
                confirmInput.addEventListener('input', checkPasswordMatch);
            }

            window.togglePassword = function(inputId) {
                const input = document.getElementById(inputId);
                const icon = input.nextElementSibling.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            };

            function checkPasswordStrength(password) {
                const bar = document.getElementById('password-strength-bar');
                const text = document.getElementById('password-strength-text');

                if (!bar || !text) return;

                let strength = 0;
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;

                const map = {
                    0: {
                        w: 0,
                        t: '-',
                        c: 'bg-gray-300',
                        text: 'text-gray-500'
                    },
                    1: {
                        w: 20,
                        t: 'Lemah',
                        c: 'bg-red-500',
                        text: 'text-red-600'
                    },
                    2: {
                        w: 40,
                        t: 'Sedang',
                        c: 'bg-amber-500',
                        text: 'text-amber-600'
                    },
                    3: {
                        w: 60,
                        t: 'Cukup',
                        c: 'bg-yellow-500',
                        text: 'text-yellow-600'
                    },
                    4: {
                        w: 80,
                        t: 'Baik',
                        c: 'bg-emerald-500',
                        text: 'text-emerald-600'
                    },
                    5: {
                        w: 100,
                        t: 'Kuat',
                        c: 'bg-emerald-600',
                        text: 'text-emerald-700'
                    },
                };

                const s = map[strength];

                bar.style.width = s.w + '%';
                bar.className = `h-full rounded-full transition-all duration-300 ${s.c}`;

                text.textContent = s.t;
                text.className = `text-xs font-medium ${s.text}`;
            }

            function checkPasswordMatch() {
                const password = passwordInput?.value;
                const confirm = confirmInput?.value;
                const matchDiv = document.getElementById('password-match');

                if (!matchDiv) return;

                if (confirm && password !== confirm) {
                    matchDiv.classList.remove('hidden');
                } else {
                    matchDiv.classList.add('hidden');
                }
            }

        });
    </script>
@endpush
