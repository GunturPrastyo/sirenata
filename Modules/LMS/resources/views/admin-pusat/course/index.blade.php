<x-dashboard::layouts.dashboard title="Daftar Course">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-pusat.dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Daftar Course</span>
                    </div>
                </li>
            </ol>
        </nav>

        <x-dashboard::filter-card 
            title="Daftar Course" 
            :total="$meta['total'] ?? 0"
            :resetUrl="route('admin-pusat.management-course.courses.index')">
            
            <x-slot name="actions">
                <a href="{{ route('admin-pusat.management-course.courses.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Create Course
                </a>
            </x-slot>

            <x-slot name="filter_inputs">
                <!-- Kategori Course -->
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Kategori Course
                    </label>
                    <div class="relative">
                        <i class="fas fa-layer-group absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="category_id" class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Per Page -->
                <div class="w-full sm:w-40">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Data per Halaman
                    </label>
                    <select name="per_page" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ([10, 20, 50, 100] as $page)
                            <option value="{{ $page }}" {{ request('per_page') == $page ? 'selected' : '' }}>
                                {{ $page }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pencarian -->
                <div class="flex-1 min-w-[240px] w-full">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Pencarian
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama course..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm table-auto">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">
                            <th class="px-4 md:px-6 py-3 text-center">No</th>
                            <th class="px-4 md:px-6 py-3 text-left">Category</th>
                            <th class="px-4 md:px-6 py-3 text-left ">Name</th>
                            <th class="px-4 md:px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                    
                    @forelse ($courses as $index => $course)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 md:px-6 py-3 text-center text-slate-700">
                                {{ $meta['current_page'] > 1 ? ($index + 1) + ($meta['per_page'] * ($meta['current_page'] - 1)) : $index + 1 }}
                            </td>

                            <td class="px-4 md:px-6 py-3 text-left text-slate-700">
                                {{ $course->category->name }}
                            </td>

                            <td class="px-4 md:px-6 py-3 text-left text-slate-700 font-medium">
                                {{ $course->name }}
                            </td>
                            <td class="px-4 md:px-6 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <x-table.action>
                                        <li>
                                            <a href="{{ route('admin-pusat.management-course.courses.edit', $course->slug) }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Edit</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Detail</a>
                                        </li>

                                        <li>
                                            <div
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                <x-modal-delete :id="$course->slug" message="Are you sure delete Course {{ $course->name }}?"
                                                :item-name="$course->name" :route="route('admin-pusat.management-course.courses.destroy', $course->slug)" />
                                            </div>
                                        </li>
                                    </x-table.action>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="10"
                                class="px-4 md:px-6 py-12 text-center text-slate-500"
                            >
                                <div class="flex flex-col items-center gap-2">
                                    <div
                                        class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-100 text-slate-400"
                                    >
                                        <i class="fas fa-list text-xl"></i>
                                    </div>

                                    <p class="text-base font-medium text-slate-700">Tidak ada data</p>

                                    <p class="text-xs text-slate-500">
                                        Kursus belum tersedia atau filter yang diterapkan belum menghasilkan data.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if (! empty($courses))
                <div class="mt-6 flex justify-center gap-2">
                    <x-api-pagination :meta="$meta" />
                </div>
            @endif
        </x-dashboard::filter-card>
    </div>
</x-dashboard::layouts.dashboard>