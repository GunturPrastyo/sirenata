<div>
    <div class="my-3">
        <x-validation-errors />
    </div>
    <x-flash-message />
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-6 mb-6 card-hover">
        <form wire:submit="save">
            <!-- Informasi Pribadi -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-indigo-100 text-indigo-600">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Informasi Pribadi</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 form-grid">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" wire:model.lazy="full_name"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 form-input"
                            placeholder="Masukkan nama lengkap">
                        @error('full_name')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" wire:model.lazy="email" disabled
                            class="w-full px-4 py-2.5 border border-gray-300 cursor-not-allowed rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 form-input"
                            placeholder="contoh@kemnaker.go.id">
                        @error('email')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="telepon" name="telepon" wire:model.lazy="phone" inputmode="numeric"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 form-input"
                            placeholder="+62 812-3456-7890">
                        @error('phone')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instansi" class="block text-sm font-medium text-gray-700 mb-2">
                            instansi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="instansi" name="instansi" wire:model.lazy="instansi"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 form-input"
                            placeholder="Contoh: Staff Administrasi">
                        @error('instansi')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="join_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Bergabung
                        </label>
                        <input type="date" id="join_date" name="join_date" disabled wire:model.lazy="join_date"
                            class="w-full px-4 py-2.5 border border-gray-300 cursor-not-allowed rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 form-input">
                    </div>

                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                            Province
                        </label>
                        <select wire:model.lazy="province" id="province" name="province"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 form-input">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($this->provinces as $province)
                                <option value="{{ $province->code }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                        @error('province')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="regency" class="block text-sm font-medium text-gray-700 mb-2">
                            Regency
                        </label>
                        <select wire:model.lazy="regency" id="regency" name="regency"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 form-input">
                            <option value="">Pilih Kabupaten/Kota</option>
                            @foreach ($this->regencies as $regency)
                                <option value="{{ $regency->code }}">{{ $regency->name }}</option>
                            @endforeach
                        </select>
                        @error('regency')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status Akun <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <div class="flex items-center">
                                <input type="radio" id="status_aktif" name="status" value="aktif"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <label for="status_aktif" class="ml-2 block text-sm text-gray-700">
                                    Aktif
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="radio" id="status_nonaktif" name="status" value="nonaktif"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <label for="status_nonaktif" class="ml-2 block text-sm text-gray-700">
                                    Nonaktif
                                </label>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>

            <!-- Informasi Akun -->

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
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @forelse ($roles as $role)
                                <div class="relative">
                                    <input type="checkbox" id="role_{{ $role->uuid }}" wire:model="roleIds"
                                        value="{{ $role->uuid }}" class="hidden peer"
                                        :disabled="$wire.roleIds.length >= 2 && !$wire.roleIds.includes('{{ $role->uuid }}')">

                                    <label for="role_{{ $role->uuid }}"
                                        class="flex flex-col h-full p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed transition">
                                        <div class="flex items-center mb-2">
                                            <div class="p-2 rounded-lg bg-indigo-100 text-indigo-600 mr-3">
                                                <i class="fas fa-user-cog"></i>
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

                        @error('roleIds')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pengaturan Notifikasi -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-emerald-100 text-emerald-600">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Pengaturan Notifikasi</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center justify-between p-3 border border-slate-200 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Email Notifikasi</p>
                            <p class="text-xs text-gray-500">Kirim notifikasi via email</p>
                        </div>
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="email_notifications" name="email_notifications"
                                class="sr-only">
                            <label for="email_notifications"
                                class="block h-6 rounded-full bg-gray-300 cursor-pointer transition-colors">
                                <span
                                    class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full transition-transform duration-300"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 action-buttons form-section">
                <button type="submit" id="btn-submit"
                    class="inline-flex items-center bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>

                <a href="{{ route('super-admin.user-management.index') }}"
                    class="inline-flex items-center bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors shadow-sm">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
