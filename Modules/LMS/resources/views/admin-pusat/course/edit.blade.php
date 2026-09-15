<x-dashboard::layouts.dashboard title="Edit Course">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[
            ['label' => 'Daftar Course', 'url' => route('admin-pusat.management-course.courses.index')],
            ['label' => 'Edit Course'],
        ]" />

        <x-validation-errors />

        <!-- Form Container -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg font-semibold text-slate-800">Informasi Course</h2>
                <p class="text-sm text-slate-500">Lengkapi data di bawah ini untuk mengedit course.</p>
            </div>

            <form action="{{ route('admin-pusat.management-course.courses.update', $course->slug) }}" method="POST"
                enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kategori (Category ID) -->
                    <div class="col-span-1 md:col-span-2">
                        <x-form.select name="category_id" id="category_id" label="Kategori" required>
                            <option value="" disabled>-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $course->category->id ?? '') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-form.select>
                    </div>

                    <!-- Nama Course -->
                    <div class="col-span-1 md:col-span-2">
                        <x-form.input name="name" label="Nama Course" :value="$course->name"
                            placeholder="Contoh: Perencanaan Tenaga Kerja" required />
                    </div>
                    <!-- Thumbnail -->
                    <div class="col-span-1 md:col-span-2">
                        <x-form.input type="file" name="thumbnail" label="Thumbnail Course (Opsional)"
                            accept="image/*"
                            class="file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak ingin mengubah file thumbnail
                            saat ini.</p>

                        <!-- Pilihan Warna Background Thumbnail Otomatis -->
                        <div class="mt-3">
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Ubah Warna Background
                                Thumbnail Otomatis (Jika tidak upload file)</label>
                            <div class="flex items-center gap-3">
                                <label
                                    class="cursor-pointer flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                    <input type="radio" name="bg_color" value="13416B"
                                        class="text-blue-900 focus:ring-blue-900">
                                    <span class="w-4 h-4 rounded-full bg-[#13416B] inline-block border"></span> Navy
                                </label>
                                <label
                                    class="cursor-pointer flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                    <input type="radio" name="bg_color" value="547996"
                                        class="text-slate-600 focus:ring-slate-600">
                                    <span class="w-4 h-4 rounded-full bg-[#547996] inline-block border"></span> Slate
                                    Blue
                                </label>
                                <label
                                    class="cursor-pointer flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                    <input type="radio" name="bg_color" value="8BB1CC"
                                        class="text-blue-400 focus:ring-blue-400">
                                    <span class="w-4 h-4 rounded-full bg-[#8BB1CC] inline-block border"></span> Light
                                    Blue
                                </label>
                                <label
                                    class="cursor-pointer flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                    <input type="radio" name="bg_color" value="79A736"
                                        class="text-lime-600 focus:ring-lime-600">
                                    <span class="w-4 h-4 rounded-full bg-[#79A736] inline-block border"></span> Muted
                                    Green
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-span-1 md:col-span-2">
                        <x-form.textarea name="description" label="Deskripsi Course" rows="5" :value="$course->description"
                            placeholder="Tuliskan deskripsi lengkap mengenai course ini..." required />
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-8 flex justify-end space-x-3 border-t border-slate-200 pt-5">
                    <x-button :href="route('admin-pusat.management-course.courses.index')" variant="white">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary">
                        Simpan Course
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Autocomplete Slug dari Nama -->
    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>
