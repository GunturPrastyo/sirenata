<x-dashboard::layouts.dashboard title="FAQ - E-Learning">
    <div class="p-2 sm:p-6">
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[
            ['label' => 'FAQ']
        ]" />

        <x-dashboard::filter-card 
            title="Daftar FAQ" 
            :total="$faqs->total() . ' FAQ'"
            :resetUrl="route($routePrefix . 'index')">
            
            <x-slot name="actions">
                <x-button :href="route('admin-pusat.faq.export') . '?' . http_build_query(request()->only(['search', 'level']))" variant="success" icon="fas fa-download" title="Ekspor Data">
                    <span class="hidden sm:inline">Ekspor</span>
                </x-button>
                @role('admin-pusat')
                <x-button x-data x-on:click="$dispatch('open-modal', 'create-faq')" variant="primary" icon="fas fa-plus">
                    Tambah FAQ
                </x-button>
                @endrole
            </x-slot>

            <x-slot name="filter_inputs">
                <!-- Level -->
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Filter Level
                    </label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="level" class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Level</option>
                            <option value="Nasional" @selected(request('level') === 'Nasional')>Nasional</option>
                            <option value="Provinsi" @selected(request('level') === 'Provinsi')>Provinsi</option>
                            <option value="Kab/Kota" @selected(request('level') === 'Kab/Kota')>Kab/Kota</option>
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

                <!-- Search -->
                <div class="flex-1 min-w-[240px] w-full">
                    <label class="block text-xs font-medium text-slate-500 mb-1">
                        Pencarian
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari FAQ..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th align="left">No.</x-table.th>
                        <x-table.th>Pertanyaan</x-table.th>
                        <x-table.th>Level</x-table.th>
                        <x-table.th>Dibuat Oleh</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($faqs as $key => $faq)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td>
                                {{ $key + $faqs->firstItem() }}
                            </x-table.td>
                            <x-table.td class="font-medium">
                                {{ Str::limit($faq->question, 60) }}
                            </x-table.td>
                            <x-table.td>
                                @if($faq->level === 'Nasional')
                                    <x-badge color="emerald" text="Nasional" />
                                @elseif($faq->level === 'Provinsi')
                                    <x-badge color="indigo" text="Provinsi" />
                                @elseif($faq->level === 'Kab/Kota')
                                    <x-badge color="amber" text="Kab/Kota" />
                                @endif
                            </x-table.td>
                            <x-table.td>
                                {{ $faq->creator->name ?? 'Sistem' }}
                            </x-table.td>
                            <x-table.td align="center">
                                <x-table.action>
                                    <li>
                                        <button x-data x-on:click="$dispatch('open-modal', 'show-faq-{{ $faq->id }}')"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-slate-700 text-xs">Detail</button>
                                    </li>
                                    @role('admin-pusat')
                                    <li>
                                        <button x-data x-on:click="$dispatch('open-modal', 'edit-faq-{{ $faq->id }}')"
                                            class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-slate-700 text-xs">Ubah</button>
                                    </li>
                                    <li>
                                        <div class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-slate-700 text-xs">
                                            <x-modal-delete :id="'delete-faq-' . $faq->id" message="Apakah Anda yakin ingin menghapus FAQ ini?"
                                                :item-name="Str::limit($faq->question, 40)" buttonText="Hapus" buttonClass="w-full text-left text-red-600 outline-none cursor-pointer" :route="route($routePrefix . 'destroy', $faq->id)" />
                                        </div>
                                    </li>
                                    @endrole
                                </x-table.action>
                            </x-table.td>
                        </tr>
                    @empty
                        <tr>
                            <x-table.td colspan="5" align="center" class="py-12">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <i class="fas fa-question-circle text-xl"></i>
                                    </div>
                                    <p class="text-base font-medium text-slate-700">Tidak ada FAQ yang ditemukan.</p>
                                </div>
                            </x-table.td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table.table>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $faqs->links('pagination::tailwind') }}
            </div>
        </x-dashboard::filter-card>
    </div>

    <x-modal name="create-faq" title="Tambah FAQ Baru">
        <form action="{{ route($routePrefix . 'store') }}" method="POST"
            x-data="{ level: '{{ old('level', 'Nasional') }}' }" class="space-y-4">
            @csrf
            <x-form.input name="question" label="Pertanyaan" :required="true" />
            
            <x-form.select name="level" label="Level" :required="true" x-model="level">
                <option value="Nasional">Nasional (Tingkat Pusat)</option>
                <option value="Provinsi">Provinsi</option>
                <option value="Kab/Kota">Kabupaten/Kota</option>
            </x-form.select>

            <x-form.textarea name="answer" label="Jawaban" :rows="5" :required="true" />

            <div class="flex justify-end gap-3 pt-2">
                <x-button type="button" x-on:click="$dispatch('close-modal', 'create-faq')" variant="white">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary">
                    Simpan FAQ
                </x-button>
            </div>
        </form>
    </x-modal>

    @foreach($faqs as $faq)
        <x-modal name="show-faq-{{ $faq->id }}" title="Detail FAQ" maxWidth="sm:max-w-2xl">
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Pertanyaan</h4>
                    <p class="text-gray-900 font-semibold">{{ $faq->question }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Level</h4>
                    @if($faq->level === 'Nasional')
                        <x-badge color="emerald" text="Nasional" />
                    @elseif($faq->level === 'Provinsi')
                        <x-badge color="indigo" text="Provinsi" />
                    @elseif($faq->level === 'Kab/Kota')
                        <x-badge color="amber" text="Kab/Kota" />
                    @endif
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Jawaban</h4>
                    <div class="prose max-w-none text-gray-800 text-sm">
                        {!! nl2br(e($faq->answer)) !!}
                    </div>
                </div>
                <div class="text-xs text-gray-400 pt-2 border-t">
                    Dibuat oleh: {{ $faq->creator->name ?? 'Sistem' }} &middot; {{ $faq->created_at->format('d M Y H:i') }}
                </div>
            </div>
        </x-modal>
    @endforeach

    @foreach($faqs as $faq)
        <x-modal name="edit-faq-{{ $faq->id }}" title="Edit FAQ">
            <form action="{{ route($routePrefix . 'update', $faq->id) }}" method="POST"
                x-data="{ level: '{{ old('level', $faq->level) }}' }" class="space-y-4">
                @csrf
                @method('PUT')
                <x-form.input name="question" label="Pertanyaan" :required="true" :value="old('question', $faq->question)" />
                
                <x-form.select name="level" label="Level" :required="true" x-model="level">
                    <option value="Nasional">Nasional (Tingkat Pusat)</option>
                    <option value="Provinsi">Provinsi</option>
                    <option value="Kab/Kota">Kabupaten/Kota</option>
                </x-form.select>

                <x-form.textarea name="answer" label="Jawaban" :rows="5" :required="true" :value="old('answer', $faq->answer)" />

                <div class="flex justify-end gap-3 pt-2">
                    <x-button type="button" x-on:click="$dispatch('close-modal', 'edit-faq-{{ $faq->id }}')" variant="white">
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary">
                        Perbarui FAQ
                    </x-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-dashboard::layouts.dashboard>