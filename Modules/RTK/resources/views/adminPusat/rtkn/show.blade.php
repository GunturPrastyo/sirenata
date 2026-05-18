<x-dashboard::layouts.dashboard title="RTKN Detail">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-pusat.dashboard') }}"
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
                        <a href="{{ route('admin-pusat.rtkn.index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Daftar Laporan
                            RTKN</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail RTKN</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="">
            <button type="button" x-data @click="$dispatch('open-modal', 'edit-user')"
                class="inline-flex mb-3 cursor-pointer items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">
                Pratinjau Dokumen Saat Ini
            </button>

            <x-modal name="edit-user" title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                <div class="border border-gray-300 rounded-md overflow-hidden">

                    @if ($rtkn->document_path && Storage::disk('public')->exists($rtkn->document_path))
                        <iframe src="{{ Storage::url($rtkn->document_path) }}"
                            class="w-full min-h-[500px] rounded-md border"></iframe>
                    @else
                        <div class="flex items-center justify-center  min-h-[500px] text-gray-400 border rounded-md">
                            Tidak ada dokumen tersimpan
                        </div>
                    @endif
                </div>

                <x-slot:footer>
                    <button @click="$dispatch('close-modal', 'edit-user')"
                        class="inline-flex items-center justify-center  px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-100 hover:text-neutral-600 hover:bg-neutral-100">Close</button>
                </x-slot:footer>
            </x-modal>
        </div>

        <div class="bg-white rounded-md shadow-sm p-10">
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
                <!-- Left Column: Form -->
                <div>
                    <div id="uploadForm" class="space-y-8">
                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama
                            </label>
                            <input type="text" id="name" name="name" value="{{ $rtkn->name }}" disabled
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 cursor-not-allowed focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan nama dokumen RTKN">
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Type RTKN
                            </label>
                            <input type="text" id="name" name="name" value="{{ $rtkn->type }}" disabled
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 cursor-not-allowed focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan nama dokumen RTKN">
                        </div>
                        <div>
                            <label for="status_verification" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <input type="text" id="status_verification" name="status_verification" value="{{ $rtkn->status_verification->label() }}" disabled
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 cursor-not-allowed focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan nama dokumen RTKN">
                        </div>
                        <div>
                            <label for="status_document" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Dokumen
                            </label>
                            <input type="text" id="status_document" name="status_document" value="{{ $rtkn->status_document->label() }}" disabled
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 cursor-not-allowed focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Masukkan nama dokumen RTKN">
                        </div>

                        <!-- Tahun Berlaku -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Berlaku Dari Tahun
                                </label>
                                <input type="number" id="start_date" name="start_date" value="{{ $rtkn->start_date }}"
                                    disabled
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 cursor-not-allowed focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="2025">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sampai Tahun
                                </label>
                                <input type="number" id="end_date" name="end_date" value="{{ $rtkn->end_date }}"
                                    disabled
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 cursor-not-allowed focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="2030">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-dashboard::layouts.dashboard>
