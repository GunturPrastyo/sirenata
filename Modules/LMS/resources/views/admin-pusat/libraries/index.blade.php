<x-dashboard::layouts.dashboard title="Perpustakaan - E-Learning">
    <div class="p-2 sm:p-6">

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


        @if ($errors->any())
            <div class="mt-2 mb-4 bg-red-50 text-red-600 p-4 rounded-lg border border-red-200">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        <x-dashboard::filter-card 
            title="Daftar Materi Perpustakaan" 
            :total="$libraries->total() . ' Materi'"
            :resetUrl="route('admin-pusat.libraries.index')">
            
            <x-slot name="actions">
                <button type="button" x-data @click="$dispatch('open-modal', 'create-library')"
                    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors cursor-pointer">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden sm:inline">Tambah Materi</span>
                    <span class="sm:hidden">Tambah</span>
                </button>
            </x-slot>

            <x-slot name="filter_inputs">
                <!-- Kategori -->
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Filter Kategori
                    </label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="library_category_id" class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Kategori</option>
                            @foreach($libraryCategories as $category)
                                <option value="{{ $category->id }}" {{ ($libraryCategoryId ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                            <option value="{{ $page }}" {{ request('per_page') == $page ? 'selected' : '' }}>{{ $page }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search -->
                <div class="flex-1 min-w-[240px] w-full">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Pencarian
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari judul materi..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs">
                            <th class="px-4 md:px-6 py-3 text-left">No.</th>
                            <th class="px-4 md:px-6 py-3 text-left">Sampul</th>
                            <th class="px-4 md:px-6 py-3 text-left">Judul</th>
                            <th class="px-4 md:px-6 py-3 text-left">Kategori</th>
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
                                    <span class="px-2 py-1 bg-slate-100 text-slate-800 border border-slate-200 rounded text-xs">{{ $library->libraryCategory->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 md:px-6 py-3">
                                    <div class="flex flex-col gap-1">
                                        @if($library->file_path)
                                            <span class="inline-flex items-center text-xs text-blue-600 cursor-pointer hover:underline" @click="$dispatch('open-modal', 'preview-modal'); $dispatch('open-preview', { url: '{{ Storage::url($library->file_path) }}', type: 'pdf' })">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                File PDF
                                            </span>
                                        @endif
                                        @if($library->video_path)
                                            <span class="inline-flex items-center text-xs text-purple-600 cursor-pointer hover:underline" @click="$dispatch('open-modal', 'preview-modal'); $dispatch('open-preview', { url: '{{ Storage::url($library->video_path) }}', type: 'video' })">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                File Video
                                            </span>
                                        @endif
                                        @if($library->external_link)
                                            @php
                                                $isYoutube = str_contains($library->external_link, 'youtube.com') || str_contains($library->external_link, 'youtu.be');
                                            @endphp
                                            @if($isYoutube)
                                                <span class="inline-flex items-center text-xs text-red-600 cursor-pointer hover:underline" @click="$dispatch('open-modal', 'preview-modal'); $dispatch('open-preview', { url: '{{ $library->external_link }}', type: 'youtube' })">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    Link YouTube
                                                </span>
                                            @else
                                                <a href="{{ $library->external_link }}" target="_blank" class="inline-flex items-center text-xs text-indigo-600 hover:underline">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                                    Link Eksternal
                                                </a>
                                            @endif
                                        @endif
                                        @if(!$library->file_path && !$library->video_path && !$library->external_link)
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        <li>
                                            <button type="button" x-data @click="$dispatch('open-modal', 'edit-library-{{ $library->id }}')"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-amber-600 cursor-pointer text-left">Ubah</button>
                                        </li>
                                        <li>
                                            <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                <x-modal-delete :id="'delete-library-' . $library->id" message="Apakah Anda yakin ingin menghapus materi ini?"
                                                    :item-name="$library->title" buttonText="Hapus" buttonClass="w-full text-left text-red-600 outline-none cursor-pointer" :route="route('admin-pusat.libraries.destroy', $library->id)" />
                                            </div>
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
        </x-dashboard::filter-card>
    </div>

    <x-modal name="preview-modal" title="Preview Materi" maxWidth="sm:max-w-4xl">
        <div x-data="{
            url: '',
            type: '',
            init() {
                window.addEventListener('open-preview', (e) => {
                    this.url = e.detail.url;
                    this.type = e.detail.type;
                    if(this.type === 'youtube') {
                        let match = this.url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/);
                        this.url = match ? 'https://www.youtube.com/embed/' + match[1] : this.url;
                    }
                });
                window.addEventListener('close-modal', (e) => {
                    if (e.detail === 'preview-modal') {
                        this.url = '';
                        this.type = '';
                    }
                });
            }
        }">
            <div class="mt-4 border border-gray-200 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden">
                <template x-if="type === 'pdf'">
                    <iframe :src="url" class="w-full h-[300px] sm:h-[500px] rounded-lg" frameborder="0"></iframe>
                </template>
                <template x-if="type === 'video'">
                    <video :src="url" class="w-full h-[300px] sm:h-[500px] rounded-lg bg-black" controls autoplay></video>
                </template>
                <template x-if="type === 'youtube'">
                    <iframe :src="url" class="w-full h-[300px] sm:h-[500px] rounded-lg bg-black" frameborder="0" allowfullscreen></iframe>
                </template>
                <template x-if="!url">
                    <div class="p-8 text-center text-gray-500">
                        Memuat konten...
                    </div>
                </template>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="button" @click="$dispatch('close-modal', 'preview-modal')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 text-sm font-medium transition">Tutup</button>
            </div>
        </div>
    </x-modal>

    <x-modal name="create-library" title="Tambah Materi Perpustakaan" maxWidth="sm:max-w-4xl">
        <form action="{{ route('admin-pusat.libraries.store') }}" method="POST" enctype="multipart/form-data"
            x-data="{
                fileType: 'document',
                previewUrl: null,
                videoPreviewUrl: null,
                linkUrl: '',
                handleFileChange(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                    } else {
                        this.previewUrl = null;
                    }
                },
                handleVideoChange(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.videoPreviewUrl = URL.createObjectURL(file);
                    } else {
                        this.videoPreviewUrl = null;
                    }
                },
                getYoutubeEmbedUrl(url) {
                    if (!url) return null;
                    let match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/);
                    return match ? 'https://www.youtube.com/embed/' + match[1] : null;
                },
                get previewContent() {
                    if (this.fileType === 'document' && this.previewUrl) return { type: 'pdf', url: this.previewUrl };
                    if (this.fileType === 'video' && this.videoPreviewUrl) return { type: 'videofile', url: this.videoPreviewUrl };
                    if (this.fileType === 'link' && this.linkUrl) {
                        let embed = this.getYoutubeEmbedUrl(this.linkUrl);
                        if (embed) return { type: 'youtube', url: embed };
                        return { type: 'link', url: this.linkUrl };
                    }
                    return null;
                }
            }" class="space-y-4">
            @csrf

            <div class="flex flex-col lg:flex-row gap-6">

                <div class="flex-1 space-y-4">

                    <div>
                        <label for="create-title" class="block text-sm font-medium text-gray-700 mb-1">
                            Judul Materi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="create-title" name="title" required value="{{ old('title') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            placeholder="Judul buku / dokumen">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="create-library-type" class="block text-sm font-medium text-gray-700 mb-1">
                                Tipe Materi <span class="text-red-500">*</span>
                            </label>
                            <select id="create-library-type" name="library_category_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach($libraryCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('library_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Gambar Sampul <span class="text-xs text-gray-500">(max 2MB)</span>
                            </label>
                            <input type="file" name="cover_image" accept="image/*"
                                class="w-full border border-gray-300 rounded-md p-1 text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>

                    <div>
                        <label for="create-description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                        <textarea id="create-description" name="description" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            placeholder="Deskripsi materi...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis File <span class="text-red-500">*</span></label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-lg border transition-colors text-sm"
                                :class="fileType === 'document' ? 'border-indigo-500 bg-indigo-50 text-indigo-700 font-semibold' : 'border-gray-300 text-gray-600 hover:bg-gray-50'">
                                <input type="radio" name="file_type_ui" value="document" x-model="fileType" class="sr-only"
                                    @change="previewUrl = null; linkUrl = ''">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Dokumen
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-lg border transition-colors text-sm"
                                :class="fileType === 'video' ? 'border-indigo-500 bg-indigo-50 text-indigo-700 font-semibold' : 'border-gray-300 text-gray-600 hover:bg-gray-50'">
                                <input type="radio" name="file_type_ui" value="video" x-model="fileType" class="sr-only"
                                    @change="previewUrl = null; linkUrl = ''">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Video
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-lg border transition-colors text-sm"
                                :class="fileType === 'link' ? 'border-indigo-500 bg-indigo-50 text-indigo-700 font-semibold' : 'border-gray-300 text-gray-600 hover:bg-gray-50'">
                                <input type="radio" name="file_type_ui" value="link" x-model="fileType" class="sr-only"
                                    @change="previewUrl = null; linkUrl = ''">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                Link
                            </label>
                        </div>
                    </div>

                    <div x-show="fileType === 'document'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            File Dokumen PDF <span class="text-xs text-gray-500">(max 20MB)</span>
                        </label>
                        <input type="file" name="file_path" accept=".pdf" @change="handleFileChange($event)"
                            class="w-full border border-gray-300 rounded-md p-1 text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                    </div>

                    <div x-show="fileType === 'video'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            File Video <span class="text-xs text-gray-500">(mp4, webm, max 50MB)</span>
                        </label>
                        <input type="file" name="video_path" accept="video/mp4,video/webm" @change="handleVideoChange($event)"
                            x-bind:disabled="fileType !== 'video'"
                            class="w-full border border-gray-300 rounded-md p-1 text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                    </div>

                    <div x-show="fileType === 'link'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            URL Link Eksternal <span class="text-red-500">*</span>
                        </label>
                        <input type="url" name="external_link" x-model="linkUrl" x-bind:required="fileType === 'link'"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            placeholder="https://...">
                    </div>
                </div>

                <div class="lg:w-96 shrink-0">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preview</label>
                    <div class="border border-gray-200 rounded-lg bg-gray-50 min-h-[280px] flex items-center justify-center overflow-hidden">

                        <template x-if="previewContent && previewContent.type === 'pdf'">
                            <iframe :src="previewContent.url" class="w-full h-[350px] rounded-lg" frameborder="0"></iframe>
                        </template>

                        <template x-if="previewContent && previewContent.type === 'videofile'">
                            <video :src="previewContent.url" class="w-full h-[280px] rounded-lg" controls></video>
                        </template>

                        <template x-if="previewContent && previewContent.type === 'youtube'">
                            <iframe :src="previewContent.url" class="w-full h-[280px] rounded-lg" frameborder="0" allowfullscreen></iframe>
                        </template>

                        <template x-if="previewContent && previewContent.type === 'link'">
                            <div class="p-4 text-center w-full">
                                <svg class="mx-auto w-10 h-10 text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                <p class="text-xs text-gray-500 mb-2">Link Eksternal:</p>
                                <a :href="linkUrl" target="_blank" class="text-sm text-indigo-600 hover:underline break-all" x-text="linkUrl"></a>
                            </div>
                        </template>

                        <template x-if="!previewContent">
                            <div class="text-center p-6">
                                <svg class="mx-auto w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <p class="text-xs text-gray-400">Pilih jenis file dan masukkan konten untuk melihat preview</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" x-data @click="$dispatch('close-modal', 'create-library')"
                    class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 text-sm text-center cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-indigo-700 text-sm cursor-pointer">
                    Simpan Materi
                </button>
            </div>
        </form>
    </x-modal>

    @foreach($libraries as $library)
        @php
            $initialFileType = 'document';
            $initialLinkUrl = '';
            $initialPreviewUrl = null;
            if ($library->external_link) {
                if (str_contains($library->external_link, 'youtube.com') || str_contains($library->external_link, 'youtu.be')) {
                    $initialFileType = 'video';
                } else {
                    $initialFileType = 'link';
                }
                $initialLinkUrl = $library->external_link;
            } elseif ($library->file_path) {
                $initialFileType = 'document';
                $initialPreviewUrl = Storage::url($library->file_path);
            }
        @endphp

        <x-modal name="edit-library-{{ $library->id }}" title="Edit Materi Perpustakaan" maxWidth="sm:max-w-4xl">
            <form action="{{ route('admin-pusat.libraries.update', $library->id) }}" method="POST" enctype="multipart/form-data"
                x-data="{
                    fileType: '{{ $initialFileType }}',
                    previewUrl: @json($initialPreviewUrl),
                    videoPreviewUrl: null,
                    linkUrl: '{{ $initialLinkUrl }}',
                    handleFileChange(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.previewUrl = URL.createObjectURL(file);
                        }
                    },
                    handleVideoChange(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.videoPreviewUrl = URL.createObjectURL(file);
                        } else {
                            this.videoPreviewUrl = null;
                        }
                    },
                    getYoutubeEmbedUrl(url) {
                        if (!url) return null;
                        let match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/);
                        return match ? 'https://www.youtube.com/embed/' + match[1] : null;
                    },
                    get previewContent() {
                        if (this.fileType === 'document' && this.previewUrl) return { type: 'pdf', url: this.previewUrl };
                        if (this.fileType === 'video' && this.videoPreviewUrl) return { type: 'videofile', url: this.videoPreviewUrl };
                        if (this.fileType === 'link' && this.linkUrl) {
                            let embed = this.getYoutubeEmbedUrl(this.linkUrl);
                            if (embed) return { type: 'youtube', url: embed };
                            return { type: 'link', url: this.linkUrl };
                        }
                        return null;
                    }
                }" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="flex flex-col lg:flex-row gap-6">

                    <div class="flex-1 space-y-4">

                        <div>
                            <label for="edit-title-{{ $library->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                Judul Materi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="edit-title-{{ $library->id }}" name="title" required
                                value="{{ old('title', $library->title) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                placeholder="Judul buku / dokumen">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="edit-type-{{ $library->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                    Kategori Materi <span class="text-red-500">*</span>
                                </label>
                                <select id="edit-type-{{ $library->id }}" name="library_category_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($libraryCategories as $category)
                                        <option value="{{ $category->id }}" {{ old('library_category_id', $library->library_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Gambar Sampul <span class="text-xs text-gray-500">(abaikan jika tidak ubah)</span>
                                </label>
                                <input type="file" name="cover_image" accept="image/*"
                                    class="w-full border border-gray-300 rounded-md p-1 text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                @if($library->cover_image)
                                    <p class="text-xs text-gray-500 mt-1">Sampul saat ini sudah terlampir.</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label for="edit-desc-{{ $library->id }}" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                            <textarea id="edit-desc-{{ $library->id }}" name="description" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                placeholder="Deskripsi materi...">{{ old('description', $library->description) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis File <span class="text-red-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-lg border transition-colors text-sm"
                                    :class="fileType === 'document' ? 'border-indigo-500 bg-indigo-50 text-indigo-700 font-semibold' : 'border-gray-300 text-gray-600 hover:bg-gray-50'">
                                    <input type="radio" name="file_type_ui" value="document" x-model="fileType" class="sr-only"
                                        @change="linkUrl = ''">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Dokumen
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-lg border transition-colors text-sm"
                                    :class="fileType === 'video' ? 'border-indigo-500 bg-indigo-50 text-indigo-700 font-semibold' : 'border-gray-300 text-gray-600 hover:bg-gray-50'">
                                    <input type="radio" name="file_type_ui" value="video" x-model="fileType" class="sr-only"
                                        @change="previewUrl = null">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Video
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer px-4 py-2 rounded-lg border transition-colors text-sm"
                                    :class="fileType === 'link' ? 'border-indigo-500 bg-indigo-50 text-indigo-700 font-semibold' : 'border-gray-300 text-gray-600 hover:bg-gray-50'">
                                    <input type="radio" name="file_type_ui" value="link" x-model="fileType" class="sr-only"
                                        @change="previewUrl = null">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                    Link
                                </label>
                            </div>
                        </div>

                        <div x-show="fileType === 'document'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                File Dokumen PDF <span class="text-xs text-gray-500">(max 20MB, abaikan jika tidak ubah)</span>
                            </label>
                            <input type="file" name="file_path" accept=".pdf" @change="handleFileChange($event)"
                                class="w-full border border-gray-300 rounded-md p-1 text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                            @if($library->file_path)
                                <p class="text-xs text-gray-500 mt-1">File PDF saat ini sudah terlampir.</p>
                            @endif
                        </div>

                        <div x-show="fileType === 'video'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                File Video <span class="text-xs text-gray-500">(mp4, webm, max 50MB, abaikan jika tidak ubah)</span>
                            </label>
                            <input type="file" name="video_path" accept="video/mp4,video/webm" @change="handleVideoChange($event)"
                                x-bind:disabled="fileType !== 'video'"
                                class="w-full border border-gray-300 rounded-md p-1 text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                        </div>

                        <div x-show="fileType === 'link'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                URL Link Eksternal <span class="text-red-500">*</span>
                            </label>
                            <input type="url" name="external_link" x-model="linkUrl" x-bind:required="fileType === 'link'"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                placeholder="https://...">
                        </div>
                    </div>

                    <div class="lg:w-96 shrink-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preview</label>
                        <div class="border border-gray-200 rounded-lg bg-gray-50 min-h-[280px] flex items-center justify-center overflow-hidden">
                            <template x-if="previewContent && previewContent.type === 'pdf'">
                                <iframe :src="previewContent.url" class="w-full h-[350px] rounded-lg" frameborder="0"></iframe>
                            </template>
                            <template x-if="previewContent && previewContent.type === 'videofile'">
                                <video :src="previewContent.url" class="w-full h-[280px] rounded-lg" controls></video>
                            </template>
                            <template x-if="previewContent && previewContent.type === 'youtube'">
                                <iframe :src="previewContent.url" class="w-full h-[280px] rounded-lg" frameborder="0" allowfullscreen></iframe>
                            </template>
                            <template x-if="previewContent && previewContent.type === 'link'">
                                <div class="p-4 text-center w-full">
                                    <svg class="mx-auto w-10 h-10 text-indigo-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    <p class="text-xs text-gray-500 mb-2">Link Eksternal:</p>
                                    <a :href="linkUrl" target="_blank" class="text-sm text-indigo-600 hover:underline break-all" x-text="linkUrl"></a>
                                </div>
                            </template>
                            <template x-if="!previewContent">
                                <div class="text-center p-6">
                                    <svg class="mx-auto w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <p class="text-xs text-gray-400">Preview konten akan muncul di sini</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" x-data @click="$dispatch('close-modal', 'edit-library-{{ $library->id }}')"
                        class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 text-sm text-center cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-indigo-700 text-sm cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-dashboard::layouts.dashboard>