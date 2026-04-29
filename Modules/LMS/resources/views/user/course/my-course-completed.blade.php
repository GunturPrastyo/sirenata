<x-dashboard::layouts.dashboard title="My Course | SIRENATA">
    <div class="p-2 sm:p-6">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a
                        href="{{ url('/') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                            ></path>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">
                            My Course
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

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
                            <span>Semua </span>
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
                            <span>Selesai ({{ $meta['total'] ?? 0 }})</span>
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