<x-dashboard::layouts.dashboard title="My Course | SIRENATA">
    <div class="p-2 sm:p-6">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}"
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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">My Course</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div x-data="{
            activeTab: 'semua',
            courses: {{ json_encode($courses) }},
            get filtered() {
                if (this.activeTab === 'semua') return this.courses
                if (this.activeTab === 'belum-selesai') return this.courses.filter(c => c.status !== 'completed')
                if (this.activeTab === 'selesai') return this.courses.filter(c => c.status === 'completed')
                return this.courses
            },
            count(tab) {
                if (tab === 'semua') return this.courses.length
                if (tab === 'belum-selesai') return this.courses.filter(c => c.status !== 'completed').length
                if (tab === 'selesai') return this.courses.filter(c => c.status === 'completed').length
                return 0
            }
        }">

            {{-- Filter Tabs --}}
            <div class="mb-4 sm:mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-2 sm:space-x-4 overflow-x-auto pb-2 -mx-2 px-2">
                        <template x-for="tab in [
                            { key: 'semua', label: 'Semua' },
                            { key: 'belum-selesai', label: 'Belum Selesai' },
                            { key: 'selesai', label: 'Selesai' }
                        ]" :key="tab.key">
                            <button
                                @click="activeTab = tab.key"
                                :class="activeTab === tab.key
                                    ? 'border-indigo-600 text-indigo-600 font-semibold'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium'"
                                class="border-b-2 py-2 sm:py-3 px-1 text-xs sm:text-sm whitespace-nowrap transition-colors">
                                <span x-text="tab.label"></span>
                                <span
                                    :class="activeTab === tab.key ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500'"
                                    class="ml-1.5 text-xs px-1.5 py-0.5 rounded-full"
                                    x-text="count(tab.key)">
                                </span>
                            </button>
                        </template>
                    </nav>
                </div>
            </div>

            {{-- Course Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="course in filtered" :key="course.id">
                    <div class="course-card bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_24px_-8px_rgba(99,102,241,0.3)] hover:border-indigo-500">
                        <img :src="course.thumbnail_url"
                            :alt="course.name"
                            class="w-full h-24 sm:h-40 object-cover">

                        <div class="p-3 sm:p-5">
                        <div class="flex items-start justify-between mb-2">
                            {{-- Logic Badge berdasarkan status dari API --}}
                            <template x-if="course.status === 'completed'">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600">
                                    Selesai
                                </span>
                            </template>

                            <template x-if="course.status === 'in_progress'">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-600">
                                    Sedang Berjalan
                                </span>
                            </template>

                            <template x-if="course.status === 'enrolled'">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                    Terdaftar
                                </span>
                            </template>

                            <span class="text-xs text-gray-500" x-text="course.progress + '%'"></span>
                        </div>

                            {{-- Nama Course --}}
                            <h3 class="font-bold text-gray-900 mb-1 sm:mb-2 text-sm sm:text-lg line-clamp-2" x-text="course.name"></h3>

                            {{-- Kategori --}}
                            <p class="text-xs text-gray-400 mb-2 sm:mb-3" x-text="course.category"></p>

                            {{-- Progress Bar --}}
                            <div class="mb-3 sm:mb-4">
                                <div class="w-full bg-gray-200 rounded-full h-1.5 sm:h-2">
                                    <div class="h-1.5 sm:h-2 rounded-full transition-all duration-300"
                                        :class="course.status === 'completed' ? 'bg-emerald-600' : 'bg-indigo-600'"
                                        :style="'width: ' + course.progress + '%'">
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <template x-if="course.status !== 'completed'">
                                <a :href="'/learning/' + course.slug"
                                    class="block w-full bg-indigo-600 text-white py-2 sm:py-2.5 rounded-lg font-medium sm:font-semibold hover:bg-indigo-700 transition-colors text-center text-xs sm:text-base">
                                    Lanjutkan Belajar
                                </a>
                            </template>
                            <template x-if="course.status === 'completed'">
                                <a :href="'/learning/' + course.slug"
                                    class="block w-full bg-emerald-600 text-white py-2 sm:py-2.5 rounded-lg font-medium sm:font-semibold hover:bg-emerald-700 transition-colors text-center text-xs sm:text-base">
                                    Detail
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Empty State --}}
            <div x-show="filtered.length === 0" class="text-center py-12 text-gray-500">
                <div class="text-4xl mb-3">📚</div>
                <p class="text-sm">Belum ada course di kategori ini.</p>
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex justify-center gap-2" x-show="filtered.length > 0">
                <x-api-pagination :meta="$meta" />
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>