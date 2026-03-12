<x-dashboard::layouts.dashboard title="Perpustakaan - E-Learning">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}"
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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Perpustakaan</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="mt-2 mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-2 mb-4 bg-red-50 text-red-600 p-4 rounded-lg border border-red-200">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Search & Filter Bar -->
        <form method="GET" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <div class="relative w-full sm:w-48">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="library_type_id" class="pl-9 pr-3 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Tipe</option>
                            @foreach($libraryTypes as $type)
                                <option value="{{ $type->id }}" {{ ($libraryTypeId ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative w-full sm:w-44">
                        <select name="per_page" class="px-3 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach ([10, 20, 50, 100] as $page)
                                <option value="{{ $page }}" {{ request('per_page') == $page ? 'selected' : '' }}>{{ $page }} / Halaman</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex w-full lg:w-96 gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul materi..."
                            class="pl-10 pr-4 py-2.5 w-full rounded-md border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                        <i class="fas fa-search text-xs"></i><span class="hidden sm:inline">Search</span>
                    </button>
                    <a href="{{ route('admin-pusat.libraries.index') }}" class="inline-flex items-center gap-2 px-4 rounded-md border border-slate-300 text-slate-600 text-sm font-medium hover:bg-slate-100 transition">
                        <i class="fas fa-rotate-left text-xs"></i><span class="hidden sm:inline">Reset</span>
                    </a>
                </div>
            </div>
        </form>

        <!-- Table Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar Materi Perpustakaan</h2>
                    <p class="text-sm text-slate-500 mt-1">Total: <span class="font-medium text-slate-700">{{ $libraries->total() }}</span> Materi</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin-pusat.libraries.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors cursor-pointer">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">Tambah Materi Baru</span>
                        <span class="sm:hidden">Tambah</span>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">
                            <th class="px-4 md:px-6 py-3 text-left">No.</th>
                            <th class="px-4 md:px-6 py-3 text-left">Sampul</th>
                            <th class="px-4 md:px-6 py-3 text-left">Judul</th>
                            <th class="px-4 md:px-6 py-3 text-left">Tipe</th>
                            <th class="px-4 md:px-6 py-3 text-left">Lampiran</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($libraries as $key => $library)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 md:px-6 py-3"><p class="text-slate-600">{{ $key + $libraries->firstItem() }}</p></td>
                                <td class="px-4 md:px-6 py-3">
                                    @if($library->cover_image)
                                        <img src="{{ Storage::url($library->cover_image) }}" alt="Cover" class="w-10 h-14 object-cover rounded shadow-sm">
                                    @else
                                        <div class="w-10 h-14 bg-slate-200 rounded flex items-center justify-center text-slate-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <p class="text-slate-600 font-medium">{{ $library->title }}</p>
                                    @if($library->description)
                                        <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $library->description }}</p>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-800 border border-slate-200 rounded text-xs">{{ $library->libraryType->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <div class="flex flex-col gap-1">
                                        @if($library->file_path)
                                            <span class="inline-flex items-center text-xs text-blue-600">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                File PDF
                                            </span>
                                        @endif
                                        @if($library->external_link)
                                            <a href="{{ $library->external_link }}" target="_blank" class="inline-flex items-center text-xs text-indigo-600 hover:underline">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                                Link Eksternal
                                            </a>
                                        @endif
                                        @if(!$library->file_path && !$library->external_link)
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        <li>
                                            <a href="{{ route('admin-pusat.libraries.edit', $library->id) }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-indigo-600 cursor-pointer">Edit</a>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin-pusat.libraries.destroy', $library->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?');"
                                                class="inline-flex items-center w-full hover:bg-slate-100 rounded m-0 p-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full text-left p-2 text-red-600">Hapus</button>
                                            </form>
                                        </li>
                                    </x-table.action>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-500">Tidak ada materi perpustakaan yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $libraries->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>