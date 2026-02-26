<x-dashboard::layouts.dashboard title="Update Permission">
    <div class="p-2 sm:p-6">
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('super-admin.dashboard') }}"
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
                        <a href="{{ route('super-admin.permissions.index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Management
                            Permission</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 hover:text-indigo-600 md:ml-2">
                            Edit Permission
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

        <x-validation-errors />
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-6 mb-6 card-hover">
            <form action="{{ route('super-admin.permissions.update', $permission->uuid) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-8">
                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 mb-2 after:ml-0.5 after:text-red-500 after:content-['*'] ">
                            Name
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 form-input"
                            placeholder="Masukkan name">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <span class="text-sm text-gray-500">example: user-create</span>
                </div>

                <div class="flex flex-wrap gap-3 action-buttons form-section w-full">
                    <button type="submit" id="btn-submit"
                        class="inline-flex cursor-pointer items-center w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm justify-center">
                        Simpan Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard::layouts.dashboard>
