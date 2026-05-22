<x-dashboard::layouts.dashboard title="Daftar Course">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[
            ['label' => 'Daftar Course']
        ]" />

        <x-dashboard::filter-card 
            title="Daftar Course" 
            :total="$meta['total'] ?? 0"
            :resetUrl="route('admin-pusat.management-course.courses.index')">
            
            <x-slot name="actions">
                <x-button :href="route('admin-pusat.management-course.courses.create')" variant="primary" icon="fas fa-plus">
                    Tambah Course
                </x-button>
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

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th align="center">No</x-table.th>
                        <x-table.th>Category</x-table.th>
                        <x-table.th>Name</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($courses as $index => $course)
                    <tr class="hover:bg-slate-50 transition">
                        <x-table.td align="center">
                            {{ $meta['current_page'] > 1 ? ($index + 1) + ($meta['per_page'] * ($meta['current_page'] - 1)) : $index + 1 }}
                        </x-table.td>

                        <x-table.td>
                            {{ $course->category->name }}
                        </x-table.td>

                        <x-table.td class="font-medium">
                            {{ $course->name }}
                        </x-table.td>
                        <x-table.td align="center">
                            <x-table.action>
                                <li>
                                    <a href="{{ route('admin-pusat.management-course.courses.edit', $course->slug) }}"
                                        class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-slate-700 text-xs">Edit</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}"
                                        class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-slate-700 text-xs">Detail</a>
                                </li>

                                <li>
                                    <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-slate-700 text-xs">
                                        <x-modal-delete :id="$course->slug" message="Are you sure delete Course {{ $course->name }}?"
                                        :item-name="$course->name" :route="route('admin-pusat.management-course.courses.destroy', $course->slug)" />
                                    </div>
                                </li>
                            </x-table.action>
                        </x-table.td>
                    </tr>
                @empty
                    <tr>
                        <x-table.td colspan="4" align="center" class="py-12">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <i class="fas fa-list text-xl"></i>
                                </div>
                                <p class="text-base font-medium text-slate-700">Tidak ada data</p>
                                <p class="text-xs text-slate-500">
                                    Kursus belum tersedia atau filter yang diterapkan belum menghasilkan data.
                                </p>
                            </div>
                        </x-table.td>
                    </tr>
                @endforelse
                </tbody>
            </x-table.table>

            {{-- Pagination --}}
            @if (! empty($courses))
                <div class="mt-6 flex justify-center gap-2">
                    <x-api-pagination :meta="$meta" />
                </div>
            @endif
        </x-dashboard::filter-card>
    </div>
</x-dashboard::layouts.dashboard>