<x-dashboard::layouts.dashboard title="User Management">
    <div class="p-2 sm:p-6">
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="../index.html"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
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
                        <a href="./index.html"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Manajemen
                            Admin</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <a href="./index.html"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Edit Admin</a>
                    </div>
                </li>
            </ol>
        </nav>

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
                        {{ $user->roles->first()->name }}
                    </p>

                    <p id="last-updated" class="text-xs text-slate-400 mt-4">
                        Terakhir diperbarui:
                        <span id="last-updated-date"
                            class="font-medium text-slate-500">{{ $user->updated_at->diffForHumans() }}</span>
                    </p>
                </div>
            </div>
        </div>

        <livewire:dashboard.super-admin.user-managemanet-edit :user="$user" />

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
