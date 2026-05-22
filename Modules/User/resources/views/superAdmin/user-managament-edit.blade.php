<x-dashboard::layouts.dashboard title="User Management Edit">
    <div class="p-2 sm:p-6">
        <x-breadcrumb :items="[['label' => 'Manajemen Admin', 'url' => route('super-admin.user-management.index')], ['label' => 'Edit Admin']]" />

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 md:p-6 mb-6">
            <div class="flex flex-col sm:flex-row items-center gap-6">

                <!-- Avatar -->
                <div class="relative shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&length=2&background=6366f1&color=fff&size=128&rounded=true&uppercase=true"
                        class="h-32 w-32 rounded-full shadow" alt="Avatar">

                    <!-- Upload Button -->
                    <label for="avatar-upload"
                        class="absolute bottom-1 right-1 inline-flex items-center justify-center
                        h-9 w-9 rounded-full bg-white border border-slate-200
                        shadow hover:bg-slate-50 cursor-pointer transition">
                        <i class="fas fa-camera text-slate-600 text-sm"></i>
                        <input type="file" id="avatar-upload" name="avatar" accept="image/*" class="hidden">
                    </label>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 text-center sm:text-left">
                    <h2 id="admin-name" class="text-xl sm:text-2xl font-semibold text-slate-900">
                        {{ $user->name }}
                    </h2>

                    <p id="admin-role" class="text-sm text-indigo-600 font-medium mt-1">
                        Role {{ $user->roles->first()->name }}
                    </p>

                    <p id="last-updated" class="text-xs text-slate-400 mt-4">
                        Terakhir diperbarui:
                        <span id="last-updated-date"
                            class="font-medium text-slate-500">{{ $user->updated_at->diffForHumans() }}</span>
                    </p>
                </div>
            </div>
        </div>


        <livewire:dashboard.super-admin.user-management-edit :user="$user" />

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Log Perubahan</h3>
                <a href="#" class="text-sm text-indigo-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3 border border-slate-200 rounded-lg">
                    <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Profil Diperbarui</p>
                        <p class="text-sm text-gray-600">Mengubah nomor telepon</p>
                        <p class="text-xs text-gray-500 mt-1">Hari ini, 10:30 - Oleh: Super Admin</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 border border-slate-200 rounded-lg">
                    <div class="p-2 rounded-lg bg-emerald-100 text-emerald-600">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Status Diubah</p>
                        <p class="text-sm text-gray-600">Mengubah status menjadi Aktif</p>
                        <p class="text-xs text-gray-500 mt-1">Kemarin, 14:20 - Oleh: Super Admin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>
