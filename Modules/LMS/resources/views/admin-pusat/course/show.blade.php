<x-dashboard::layouts.dashboard title="Detail Course: {{ $course->name }}">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-3">
                <li class="inline-flex items-center">
                    <a
                        href="{{ route('admin-pusat.dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-indigo-600"
                    >
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <a
                            href="{{ route('admin-pusat.management-course.courses.index') }}"
                            class="ml-1 text-sm font-medium text-slate-700 hover:text-indigo-600 md:ml-2"
                        >
                            Daftar Course
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">Detail</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- KOLOM KIRI (Utama: Info & Kurikulum) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card Info Utama -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    @if (! empty($course->thumbnail_url))
                        <img
                            src="{{ $course->thumbnail_url }}"
                            alt="{{ $course->name }}"
                            class="w-full h-72 object-cover"
                        />
                    @else
                        <div class="w-full h-64 bg-slate-200 flex items-center justify-center">
                            <i class="fas fa-image text-slate-400 text-4xl"></i>
                        </div>
                    @endif

                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-full"
                            >
                                {{ $course->category->name ?? 'Tanpa Kategori' }}
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-slate-800 mb-4">{{ $course->name }}</h1>

                        <div class="prose prose-sm sm:prose max-w-none text-slate-600">
                            <h3 class="text-lg font-semibold text-slate-800 mb-2">
                                Deskripsi Course
                            </h3>
                            <p>{{ $course->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card Kurikulum / Sections -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Header Kurikulum dengan Tombol Tambah Bagian -->
                    <div
                        class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center"
                    >
                        <h2 class="text-lg font-semibold text-slate-800">
                            Kurikulum / Modul Belajar
                        </h2>

                        <button
                            x-data
                            @click="$dispatch('open-modal', 'course_sections-{{ $course->slug }}')"
                            type="button"
                            class="px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-colors shadow-sm"
                        >
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Bagian
                        </button>
                    </div>

                    <x-modal
                        name="course_sections-{{ $course->slug }}"
                        title="Create New Section for {{ $course->name }}"
                        maxWidth="sm:max-w-xl"
                    >
                        <x-validation-errors class="mb-4" />
                        <form
                            action="{{ route('admin-pusat.management-course.course-sections.store') }}"
                            method="POST"
                        >
                            @csrf
                            <input type="hidden" name="course_slug" value="{{ $course->slug }}" />
                            <div class="mb-5">
                                <h3 class="text-lg font-semibold text-slate-800">
                                    Tambah Bagian Baru
                                </h3>
                                <p class="text-sm text-slate-500">
                                    Buat struktur bagian (section) untuk materi course Anda.
                                </p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        for="section_name"
                                        class="block text-sm font-medium text-slate-700 mb-1"
                                    >
                                        Nama Bagian
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="section_name"
                                        required
                                        placeholder="Contoh: Bab 1: Pengenalan Dasar"
                                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="section_description"
                                        class="block text-sm font-medium text-slate-700 mb-1"
                                    >
                                        Deskripsi
                                        <span class="text-slate-400 font-normal">(Opsional)</span>
                                    </label>
                                    <textarea
                                        name="description"
                                        id="section_description"
                                        rows="3"
                                        placeholder="Tuliskan gambaran singkat mengenai bagian ini..."
                                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                    ></textarea>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-slate-100">
                                <button
                                    type="button"
                                    x-data
                                    @click="$dispatch('close-modal', 'course_sections-{{ $course->slug }}')"
                                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 transition-colors"
                                >
                                    Simpan Bagian
                                </button>
                            </div>
                        </form>
                    </x-modal>

                    <div class="p-6">
                        @if (count($course->course_sections) > 0)
                            <div class="space-y-4">
                                <div class="space-y-4">
                                    @foreach ($course->course_sections as $section)
                                        <div
                                            class="border border-slate-200 rounded-lg overflow-hidden shadow-sm"
                                        >
                                            <!-- Header Section -->
                                            <div
                                                class="bg-slate-50 px-4 py-3 border-b border-slate-200 flex justify-between items-center"
                                            >
                                                <h3 class="font-medium text-slate-800">
                                                    Bagian {{ $section->position }}:
                                                    {{ $section->name }}
                                                </h3>
                                                <span
                                                    class="text-xs font-medium text-slate-500 bg-slate-200 px-2 py-1 rounded-md"
                                                >
                                                    {{ count($section->contents) }} Materi
                                                </span>
                                            </div>

                                            <!-- List Content -->
                                            <ul class="divide-y divide-slate-100">
                                                @forelse ($section->contents as $content)
                                                    <li
                                                        class="px-4 py-3 hover:bg-slate-50 flex items-start gap-3 transition-colors group"
                                                    >
                                                        <!-- Ikon Tipe Konten -->
                                                        <div class="mt-0.5 text-indigo-500">
                                                            @if ($content->video_url)
                                                                <i class="fas fa-play-circle"></i>
                                                            @else
                                                                <i class="fas fa-file-alt"></i>
                                                            @endif
                                                        </div>

                                                        <!-- Nama Materi -->
                                                        <div class="flex-1">
                                                            <p
                                                                class="text-sm text-slate-700 font-medium"
                                                            >
                                                                {{ $content->position }}.
                                                                {{ $content->name }}
                                                            </p>
                                                        </div>

                                                        <!-- Tombol Aksi -->
                                                        <div class="flex items-center gap-2">
                                                            <!-- Lihat -->
                                                            <button
                                                                type="button"
                                                                class="text-slate-400 hover:text-blue-600 transition-colors p-1"
                                                                @click="$dispatch('open-modal', 'show-content-{{ $content->id }}')"
                                                                title="Lihat"
                                                            >
                                                                <i class="fas fa-eye text-sm"></i>
                                                            </button>

                                                            <!-- Edit (Trigger Modal Edit) -->
                                                            <button
                                                                type="button"
                                                                x-data
                                                                @click="$dispatch('open-modal', 'edit-content-{{ $content->id }}')"
                                                                class="text-slate-400 hover:text-amber-600 transition-colors p-1"
                                                                title="Edit"
                                                            >
                                                                <i class="fas fa-edit text-sm"></i>
                                                            </button>

                                                            <!-- Delete (Trigger Modal/Konfirmasi Hapus) -->
                                                            <button
                                                                type="button"
                                                                x-data
                                                                @click="$dispatch('open-modal', 'delete-content-{{ $content->id }}')"
                                                                class="text-slate-400 hover:text-red-600 transition-colors p-1"
                                                                title="Hapus"
                                                            >
                                                                <i
                                                                    class="fas fa-trash-alt text-sm"
                                                                ></i>
                                                            </button>
                                                        </div>

                                                        {{-- Modal View Content --}}
                                                        {{-- Helper Konversi URL (tetap di posisi yang sama) --}}
                                                        @php
                                                            $embedUrl = null;
                                                            if ($content->video_url) {
                                                                $url = $content->video_url;
                                                                if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
                                                                    $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                                                } elseif (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
                                                                    $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                                                } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
                                                                    $embedUrl = $url;
                                                                } else {
                                                                    $embedUrl = $url;
                                                                }
                                                            }
                                                        @endphp

                                                        <x-modal
                                                            name="show-content-{{ $content->id }}"
                                                            title="Lihat Materi"
                                                            maxWidth="sm:max-w-3xl"
                                                        >
                                                            <div
                                                                x-data="{ videoUrl: '{{ $embedUrl }}' }"
                                                                x-on:close-modal.window="if ($event.detail.name === 'show-content-{{ $content->id }}') { 
                                                                    let tempUrl = videoUrl; 
                                                                    videoUrl = ''; 
                                                                    setTimeout(() => videoUrl = tempUrl, 100); 
                                                                }"
                                                            >
                                                                {{-- Header --}}
                                                                <div
                                                                    class="px-6 py-4 border-b border-slate-100 flex items-start justify-between gap-3"
                                                                >
                                                                    <div>
                                                                        <p
                                                                            class="text-xs text-slate-400 mb-0.5"
                                                                        >
                                                                            Materi
                                                                        </p>
                                                                        <h2
                                                                            class="text-base font-bold text-slate-800 leading-tight"
                                                                        >
                                                                            {{ $content->name }}
                                                                        </h2>
                                                                    </div>
                                                                    <button
                                                                        type="button"
                                                                        @click="$dispatch('close-modal', 'show-content-{{ $content->id }}')"
                                                                        class="shrink-0 p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                                                                    >
                                                                        <svg
                                                                            class="w-4 h-4"
                                                                            fill="none"
                                                                            stroke="currentColor"
                                                                            viewBox="0 0 24 24"
                                                                        >
                                                                            <path
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M6 18L18 6M6 6l12 12"
                                                                            />
                                                                        </svg>
                                                                    </button>
                                                                </div>

                                                                <div class="p-6 space-y-5">
                                                                    {{-- Video Section --}}
                                                                    @if ($embedUrl)
                                                                        <div>
                                                                            <p
                                                                                class="text-xs font-medium text-slate-500 mb-2 flex items-center gap-1.5"
                                                                            >
                                                                                <svg
                                                                                    class="w-3.5 h-3.5"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24"
                                                                                >
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"
                                                                                    />
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                                                    />
                                                                                </svg>
                                                                                Video Materi
                                                                            </p>
                                                                            <div
                                                                                class="aspect-video w-full rounded-xl overflow-hidden shadow-sm border border-slate-200 bg-black"
                                                                            >
                                                                                {{-- x-bind:src akan mengosongkan iframe saat videoUrl kosong --}}
                                                                                <iframe
                                                                                    class="w-full h-full"
                                                                                    x-bind:src="videoUrl"
                                                                                    frameborder="0"
                                                                                    allow="
                                                                                        accelerometer;
                                                                                        autoplay;
                                                                                        clipboard-write;
                                                                                        encrypted-media;
                                                                                        gyroscope;
                                                                                        picture-in-picture;
                                                                                    "
                                                                                    allowfullscreen
                                                                                ></iframe>
                                                                            </div>
                                                                            <div
                                                                                class="mt-2 flex justify-end"
                                                                            >
                                                                                <a
                                                                                    href="{{ $content->video_url }}"
                                                                                    target="_blank"
                                                                                    class="inline-flex items-center gap-1 text-xs text-slate-400 hover:text-indigo-600 transition-colors"
                                                                                >
                                                                                    <svg
                                                                                        class="w-3 h-3"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24"
                                                                                    >
                                                                                        <path
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                                                        />
                                                                                    </svg>
                                                                                    Buka di YouTube
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    {{-- Document Section --}}
                                                                    @if ($content->document_url)
                                                                        <div>
                                                                            <p
                                                                                class="text-xs font-medium text-slate-500 mb-2 flex items-center gap-1.5"
                                                                            >
                                                                                <svg
                                                                                    class="w-3.5 h-3.5"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24"
                                                                                >
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                                                    />
                                                                                </svg>
                                                                                Dokumen Pendukung
                                                                            </p>
                                                                            <div
                                                                                class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between hover:border-indigo-200 transition-colors"
                                                                            >
                                                                                <div
                                                                                    class="flex items-center gap-3"
                                                                                >
                                                                                    <div
                                                                                        class="p-2.5 bg-white rounded-lg border border-slate-100 shadow-sm shrink-0"
                                                                                    >
                                                                                        <svg
                                                                                            class="w-5 h-5 text-red-500"
                                                                                            fill="currentColor"
                                                                                            viewBox="0 0 24 24"
                                                                                        >
                                                                                            <path
                                                                                                d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM6 20V4h5v7h7v9H6z"
                                                                                            />
                                                                                        </svg>
                                                                                    </div>
                                                                                    <div>
                                                                                        <p
                                                                                            class="text-sm font-medium text-slate-700"
                                                                                        >
                                                                                            Dokumen
                                                                                        </p>
                                                                                        <p
                                                                                            class="text-xs text-slate-400"
                                                                                        >
                                                                                            Klik
                                                                                            untuk
                                                                                            membuka
                                                                                            dokumen
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <a
                                                                                    href="{{ $content->document_url }}"
                                                                                    target="_blank"
                                                                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition-colors shrink-0"
                                                                                >
                                                                                    <svg
                                                                                        class="w-3.5 h-3.5"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24"
                                                                                    >
                                                                                        <path
                                                                                            stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                                                        />
                                                                                    </svg>
                                                                                    Buka
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    {{-- Empty State --}}
                                                                    @if (! $embedUrl && ! $content->document_url)
                                                                        <div
                                                                            class="text-center py-10 text-slate-400"
                                                                        >
                                                                            <svg
                                                                                class="w-12 h-12 mx-auto mb-3 opacity-40"
                                                                                fill="none"
                                                                                stroke="currentColor"
                                                                                viewBox="0 0 24 24"
                                                                            >
                                                                                <path
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="1.5"
                                                                                    d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"
                                                                                />
                                                                            </svg>
                                                                            <p
                                                                                class="text-sm font-medium"
                                                                            >
                                                                                Belum ada media
                                                                            </p>
                                                                            <p class="text-xs mt-1">
                                                                                Materi ini belum
                                                                                memiliki video atau
                                                                                dokumen pendukung.
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                {{-- Footer --}}
                                                                <div
                                                                    class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end"
                                                                >
                                                                    <button
                                                                        type="button"
                                                                        @click="$dispatch('close-modal', 'show-content-{{ $content->id }}')"
                                                                        class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors"
                                                                    >
                                                                        Tutup
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </x-modal>

                                                        <!-- Modal Edit Content -->
                                                        <x-modal
                                                            name="edit-content-{{ $content->id }}"
                                                            title="Edit Materi"
                                                            maxWidth="sm:max-w-xl"
                                                            x-init="$errors->has('name') && old('content_id') == '{{ $content->id }}' ? $dispatch('open-modal', 'edit-content-{{ $content->id }}') : null"
                                                        >
                                                            <!-- Tambahkan enctype agar file bisa terkirim -->
                                                            <form
                                                                action="{{ route('admin-pusat.management-course.course-sections-contents.update', $content->id) }}"
                                                                method="POST"
                                                                enctype="multipart/form-data"
                                                                class="p-6"
                                                            >
                                                                @csrf
                                                                @method('PUT')

                                                                <input
                                                                    type="hidden"
                                                                    name="course_slug"
                                                                    value="{{ $course->slug }}"
                                                                />
                                                                <input
                                                                    type="hidden"
                                                                    name="content_id"
                                                                    value="{{ $content->id }}"
                                                                />

                                                                <div class="mb-5">
                                                                    <h3
                                                                        class="text-lg font-semibold text-slate-800"
                                                                    >
                                                                        Edit Materi
                                                                    </h3>
                                                                    @if ($content->document_url)
                                                                        <div class="mt-2 text-sm">
                                                                            <span
                                                                                class="text-slate-500"
                                                                            >
                                                                                Dokumen saat ini:
                                                                            </span>
                                                                            <a
                                                                                href="{{ $content->document_url }}"
                                                                                target="_blank"
                                                                                class="text-indigo-600 hover:underline"
                                                                            >
                                                                                Lihat Dokumen
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <div class="space-y-4">
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-medium text-slate-700 mb-1"
                                                                        >
                                                                            Nama Content
                                                                        </label>
                                                                        <input
                                                                            type="text"
                                                                            name="name"
                                                                            required
                                                                            value="{{ old('name', $content->name) }}"
                                                                            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                                                        />
                                                                    </div>

                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-medium text-slate-700 mb-1"
                                                                        >
                                                                            Video URL
                                                                        </label>
                                                                        <input
                                                                            type="text"
                                                                            name="video"
                                                                            value="{{ old('video_url', $content->video_url) }}"
                                                                            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                                                        />
                                                                    </div>

                                                                    <!-- Tambahkan input file untuk Update Dokumen -->
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-medium text-slate-700 mb-1"
                                                                        >
                                                                            Update Dokumen
                                                                            (Opsional)
                                                                        </label>
                                                                        <input
                                                                            type="file"
                                                                            name="document"
                                                                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                                                        />
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="flex justify-end gap-3 mt-8"
                                                                >
                                                                    <button
                                                                        type="button"
                                                                        @click="$dispatch('close-modal', 'edit-content-{{ $content->id }}')"
                                                                        class="px-4 py-2 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg"
                                                                    >
                                                                        Batal
                                                                    </button>
                                                                    <button
                                                                        type="submit"
                                                                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg"
                                                                    >
                                                                        Update
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </x-modal>

                                                        {{-- Modal Hapus Content Materi --}}
                                                        <x-modal
                                                            name="delete-content-{{ $content->id }}"
                                                            title="Konfirmasi Hapus"
                                                            maxWidth="sm:max-w-xl"
                                                        >
                                                            <form
                                                                action="{{ route('admin-pusat.management-course.course-sections-contents.destroy', $content->id) }}"
                                                                method="POST"
                                                                class="p-6"
                                                            >
                                                                @csrf
                                                                @method('DELETE')
                                                                <input
                                                                    type="hidden"
                                                                    name="course_slug"
                                                                    value="{{ $course->slug }}"
                                                                />

                                                                <div class="text-center">
                                                                    <h3
                                                                        class="text-lg font-semibold text-slate-800"
                                                                    >
                                                                        Hapus Materi
                                                                    </h3>
                                                                    <p
                                                                        class="text-sm text-slate-500 mt-2"
                                                                    >
                                                                        Yakin hapus
                                                                        <strong>
                                                                            "{{ $content->name }}"
                                                                        </strong>
                                                                        ?
                                                                    </p>
                                                                </div>
                                                                <div
                                                                    class="flex justify-center gap-3 mt-6"
                                                                >
                                                                    <button
                                                                        type="button"
                                                                        x-data
                                                                        @click="$dispatch('close-modal', 'delete-content-{{ $content->id }}')"
                                                                        class="px-4 py-2 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg"
                                                                    >
                                                                        Batal
                                                                    </button>
                                                                    <button
                                                                        type="submit"
                                                                        class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg"
                                                                    >
                                                                        Ya, Hapus
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </x-modal>
                                                    </li>
                                                @empty
                                                    <li
                                                        class="px-4 py-4 text-sm text-slate-500 text-center bg-slate-50/50"
                                                    >
                                                        Belum ada materi di bagian ini.
                                                    </li>
                                                @endforelse
                                            </ul>

                                            <!-- Footer: Tombol Tambah Materi -->
                                            <div
                                                class="bg-white px-4 py-3 border-t border-slate-100 flex justify-center"
                                            >
                                                <button
                                                    type="button"
                                                    x-data
                                                    @click="$dispatch('open-modal', 'section-content-{{ $section->id }}')"
                                                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center transition-colors"
                                                >
                                                    <i class="fas fa-plus-circle mr-1.5"></i>
                                                    Tambah Materi Baru
                                                </button>
                                            </div>

                                            <!-- Modal Content -->
                                            <x-modal
                                                name="section-content-{{ $section->id }}"
                                                title="Create New Content"
                                                maxWidth="sm:max-w-xl"
                                                x-init="$errors->has('course_section_id') && old('course_section_id') == '{{ $section->id }}' ? $dispatch('open-modal', 'section-content-{{ $section->id }}') : null"
                                            >
                                                <div class="p-6">
                                                    <x-validation-errors class="mb-4" />

                                                    <form
                                                        action="{{ route('admin-pusat.management-course.course-sections-contents.store') }}"
                                                        enctype="multipart/form-data"
                                                        method="POST"
                                                    >
                                                        @csrf
                                                        <input
                                                            type="hidden"
                                                            name="course_section_id"
                                                            value="{{ $section->id }}"
                                                        />
                                                        <input
                                                            type="hidden"
                                                            name="course_slug"
                                                            value="{{ $course->slug }}"
                                                        />

                                                        <div class="mb-5">
                                                            <h3
                                                                class="text-lg font-semibold text-slate-800"
                                                            >
                                                                Tambah Materi Baru
                                                            </h3>
                                                            <p class="text-sm text-slate-500">
                                                                Buat struktur materi untuk bagian:
                                                                <strong>
                                                                    {{ $section->name }}
                                                                </strong>
                                                            </p>
                                                        </div>

                                                        <div class="space-y-4">
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-slate-700 mb-1"
                                                                >
                                                                    Nama Content
                                                                    <span class="text-red-500">
                                                                        *
                                                                    </span>
                                                                </label>
                                                                <input
                                                                    type="text"
                                                                    name="name"
                                                                    required
                                                                    value="{{ old('name') }}"
                                                                    placeholder="Contoh: Apa itu Laravel?"
                                                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                                                />
                                                            </div>

                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-slate-700 mb-1"
                                                                >
                                                                    Video URL
                                                                    <span
                                                                        class="text-slate-400 font-normal"
                                                                    >
                                                                        (Opsional)
                                                                    </span>
                                                                </label>
                                                                <input
                                                                    type="text"
                                                                    name="video"
                                                                    value="{{ old('video_url') }}"
                                                                    placeholder="https://youtube.com/..."
                                                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                                                />
                                                            </div>

                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-slate-700 mb-1"
                                                                >
                                                                    Dokumen Materi
                                                                    <span
                                                                        class="text-slate-400 font-normal"
                                                                    >
                                                                        (Opsional)
                                                                    </span>
                                                                </label>
                                                                <input
                                                                    type="file"
                                                                    name="document"
                                                                    accept=".pdf,.doc,.docx"
                                                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                                                />
                                                                <p
                                                                    class="text-[10px] text-slate-400 mt-1"
                                                                >
                                                                    Format: PDF, DOC, DOCX (Max:
                                                                    10MB)
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="flex justify-end gap-3 mt-8 pt-4 border-t border-slate-100"
                                                        >
                                                            <button
                                                                type="button"
                                                                x-data
                                                                @click="$dispatch('close-modal', 'section-content-{{ $section->id }}')"
                                                                class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors"
                                                            >
                                                                Batal
                                                            </button>
                                                            <button
                                                                type="submit"
                                                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 transition-colors"
                                                            >
                                                                Simpan Materi
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </x-modal>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- Tampilan jika belum ada section sama sekali -->
                            <div class="text-center py-10">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-500 mb-4"
                                >
                                    <i class="fas fa-folder-open text-2xl"></i>
                                </div>
                                <h3 class="text-base font-medium text-slate-800 mb-1">
                                    Kurikulum Kosong
                                </h3>
                                <p class="text-slate-500 text-sm mb-4">
                                    Mulai bangun struktur materi course Anda dengan menambahkan
                                    bagian pertama.
                                </p>
                                <!-- Tombol Tambah Bagian (Empty State) -->
                                <button
                                    type="button"
                                    x-data
                                    @click="$dispatch('open-modal', 'course_sections-{{ $course->slug }}')"
                                    class="px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition-colors"
                                >
                                    <i class="fas fa-plus mr-1"></i>
                                    Buat Bagian Pertama
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN (Sidebar: Aksi & Meta Data) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Card Aksi -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6"
                >
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">
                        Aksi Course
                    </h3>
                    <div class="space-y-3">
                        <a
                            href="{{ route('admin-pusat.management-course.courses.edit', $course->slug) }}"
                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-amber-500 border border-transparent rounded-lg hover:bg-amber-600 transition-colors shadow-sm"
                        >
                            <i class="fas fa-edit mr-2"></i>
                            Edit Course
                        </a>
                        <a
                            href="{{ route('admin-pusat.management-course.courses.index') }}"
                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors shadow-sm"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>

                <!-- Card Informasi Tambahan -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                            Informasi Tambahan
                        </h3>
                    </div>
                    <div class="p-0">
                        <ul class="divide-y divide-slate-100">
                            <li class="flex justify-between items-center px-6 py-3.5">
                                <span class="text-sm text-slate-500">Total Siswa</span>
                                <span class="text-sm font-semibold text-slate-800">
                                    {{ $course->students_count ?? 0 }} Orang
                                </span>
                            </li>
                            <li class="flex justify-between items-center px-6 py-3.5">
                                <span class="text-sm text-slate-500">Kategori</span>
                                <span class="text-sm font-semibold text-slate-800">
                                    {{ $course->category->name ?? '-' }}
                                </span>
                            </li>
                            <li class="flex justify-between items-center px-6 py-3.5">
                                <span class="text-sm text-slate-500">Dibuat Pada</span>
                                <span class="text-sm font-semibold text-slate-800">
                                    {{ \Carbon\Carbon::parse($course->created_at)->translatedFormat('d M Y') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Placeholder untuk Fitur Mendatang (Benefits/Testimoni) -->
                <div
                    class="bg-indigo-50 rounded-xl border border-indigo-100 p-5 flex items-start gap-3"
                >
                    <i class="fas fa-info-circle text-indigo-500 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-indigo-800 mb-1">
                            Pengaturan Lanjutan
                        </h4>
                        <p class="text-xs text-indigo-600 leading-relaxed">
                            Kelola Mentor, Benefits, dan Testimoni secara terpisah melalui panel
                            navigasi utama.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>
