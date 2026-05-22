<x-dashboard::layouts.dashboard title="My Course | SIRENATA">
    <div class="p-2 sm:p-6">
        {{-- Breadcrumb --}}
        <x-breadcrumb :items="[['label' => 'Kursus Saya']]" />

        <div>
            <div class="mb-4 sm:mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-2 sm:space-x-4 overflow-x-auto pb-2 -mx-2 px-2">
                        <a
                            href="{{ route('user.course.my-course') }}"
                            class="border-b-2 py-2 sm:py-3 px-1 text-xs sm:text-sm whitespace-nowrap transition-colors {{
                                request()->routeIs('user.course.my-course')
                                    ? 'border-indigo-600 text-indigo-600 font-semibold'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium'
                            }}"
                        >
                            <span>Semua ({{ $meta['total'] ?? 0 }})</span>
                        </a>

                        <a
                            href="{{ route('user.course.my-course.progress') }}"
                            class="border-b-2 py-2 sm:py-3 px-1 text-xs sm:text-sm whitespace-nowrap transition-colors {{
                                request()->routeIs('user.course.my-course.progress')
                                    ? 'border-indigo-600 text-indigo-600 font-semibold'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium'
                            }}"
                        >
                            <span>Belum Selesai</span>
                        </a>

                        <a
                            href="{{ route('user.course.my-course.finish') }}"
                            class="border-b-2 py-2 sm:py-3 px-1 text-xs sm:text-sm whitespace-nowrap transition-colors {{
                                request()->routeIs('user.course.my-course.finish')
                                    ? 'border-indigo-600 text-indigo-600 font-semibold'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium'
                            }}"
                        >
                            <span>Selesai</span>
                        </a>
                    </nav>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($courses as $course)
                    <x-course-card :course="$course" />
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <div class="text-4xl mb-3">📚</div>
                        <p class="text-sm">Belum ada course di kategori ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if (! empty($courses))
                <div class="mt-6 flex justify-center gap-2">
                    <x-api-pagination :meta="$meta" />
                </div>
            @endif
        </div>
    </div>
</x-dashboard::layouts.dashboard>