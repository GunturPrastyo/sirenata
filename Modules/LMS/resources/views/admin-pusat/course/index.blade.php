<x-dashboard::layouts.dashboard title="Daftar Course">
    <div class="p-2 sm:p-6">


        <x-dashboard::filter-card title="Daftar Course" :total="$meta['total'] ?? 0" :resetUrl="route('admin-pusat.management-course.courses.index')">

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
                        <i
                            class="fas fa-layer-group absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="category_id"
                            class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                    <select name="per_page"
                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama course..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <!-- Wrapper Padding agar card tidak mepet ke container filter-card -->
            <div class="p-4 sm:p-6 lg:p-8 mt-2">
                <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 gap-8">
                    @forelse ($courses as $index => $course)
                        <div
                            class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:shadow-md transition-all duration-200">

                            <!-- Thumbnail Section -->
                            <div class="relative h-48 w-full bg-slate-100 group">
                                @if (!empty($course->thumbnail_url))
                                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-slate-400 text-3xl"></i>
                                    </div>
                                @endif

                                <!-- Header Overlay (Badge & Edit Button) -->
                                <div class="absolute top-3 inset-x-3 flex items-start justify-between">
                                    <!-- Kategori Badge -->
                                    <span
                                        class="px-3 py-1.5 text-[11px] font-medium text-slate-600 bg-white/80 backdrop-blur-md rounded-full shadow-sm border border-white/50">
                                        {{ $course->category->name ?? 'Tanpa Kategori' }}
                                    </span>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin-pusat.management-course.courses.edit', $course->slug) }}"
                                        class="flex items-center justify-center w-8 h-8 text-slate-500 bg-white/80 hover:bg-white hover:text-slate-700 backdrop-blur-md rounded-full shadow-sm border border-white/50 transition-all duration-200"
                                        title="Edit Course">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Body (Title & Description) -->
                            <div class="p-5 flex-1 flex flex-col">
                                <h3 class="text-base font-bold text-slate-800 mb-2 line-clamp-2"
                                    title="{{ $course->name }}">
                                    {{ $course->name }}
                                </h3>
                                <p class="text-sm text-slate-500 mb-4 line-clamp-3 flex-1 leading-relaxed">
                                    {{ $course->description ?? 'Tidak ada deskripsi tersedia untuk course ini.' }}
                                </p>
                            </div>

                            <!-- Footer (Actions) -->
                            <div
                                class="px-5 py-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-2">

                                <!-- Detail Text Button -->
                                <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}"
                                    class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                                    title="Lihat Detail Course">
                                    Lihat Detail
                                </a>

                                <div class="flex items-center gap-1">
                                    <!-- Delete (Modal Component) -->
                                    <div class="inline-flex items-center p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer"
                                        title="Hapus Course">
                                        <x-modal-delete :id="$course->slug"
                                            message="Are you sure delete Course {{ $course->name }}?" :item-name="$course->name"
                                            :route="route(
                                                'admin-pusat.management-course.courses.destroy',
                                                $course->slug,
                                            )" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="col-span-full py-16 bg-white rounded-xl border border-slate-200 border-dashed">
                            <div class="flex flex-col items-center gap-3">
                                <div
                                    class="w-14 h-14 flex items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <i class="fas fa-list text-2xl"></i>
                                </div>
                                <p class="text-base font-medium text-slate-700">Tidak ada data</p>
                                <p class="text-sm text-slate-500">
                                    Kursus belum tersedia atau filter yang diterapkan belum menghasilkan data.
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pagination --}}
            @if (!empty($courses))
                <div class="mt-4 mb-6 flex justify-center gap-2">
                    <x-api-pagination :meta="$meta" />
                </div>
            @endif
        </x-dashboard::filter-card>
    </div>
</x-dashboard::layouts.dashboard>
