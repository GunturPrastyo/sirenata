<x-dashboard::layouts.dashboard title="Edit Course">
    <div class="p-2 sm:p-6">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[
            ['label' => 'Daftar Course', 'url' => route('admin-pusat.management-course.courses.index')],
            ['label' => 'Edit Course']
        ]" />

        <x-validation-errors />

        <!-- Form Container -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg font-semibold text-slate-800">Informasi Course</h2>
                <p class="text-sm text-slate-500">Lengkapi data di bawah ini untuk mengedit course.</p>
            </div>

            <form action="{{ route('admin-pusat.management-course.courses.update', $course->slug) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kategori (Category ID) -->
                    <div class="col-span-1 md:col-span-2">
                        <x-form.select name="category_id" id="category_id" label="Kategori" required>
                            <option value="" disabled>-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $course->category->id ?? '') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-form.select>
                    </div>

                    <!-- Nama Course -->
                    <div class="col-span-1 md:col-span-2">
                        <x-form.input name="name" label="Nama Course" :value="$course->name" placeholder="Contoh: Perencanaan Tenaga Kerja" required />
                    </div>

                    <!-- Thumbnail -->
                    <div class="col-span-1 md:col-span-2">
                        <x-form.input type="file" name="thumbnail" label="Thumbnail Course" accept="image/*"
                            class="file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-span-1 md:col-span-2">
                        <x-form.textarea name="description" label="Deskripsi Course" rows="5" :value="$course->description" placeholder="Tuliskan deskripsi lengkap mengenai course ini..." required />
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