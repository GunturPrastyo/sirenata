<x-dashboard::layouts.dashboard title="Rekapitulasi Rencana Tenaga Kerja Nasional">
    <div class="p-2 sm:p-6 pt-6 sm:pt-8">
        <!-- Breadcrumb Navigation -->
        <x-breadcrumb :home="route('admin-pusat.dashboard')" :items="[['label' => 'RTK Nasional']]" />

        <x-dashboard::filter-card 
            title="Daftar Rekapitulasi Rencana Tenaga Kerja Nasional" 
            :total="$rtkns->total()"
            :resetUrl="route('admin-pusat.rtkn.index')">
            
            <x-slot name="actions">
                <x-button :href="route('admin-pusat.rtkn.export') . '?' . http_build_query(request()->only(['search', 'status_document', 'status_verification', 'acuan']))" variant="success" icon="fas fa-download" title="Ekspor Data">
                    <span class="hidden sm:inline">Ekspor</span>
                </x-button>

                <x-button :href="route('admin-pusat.rtkn.create')" variant="primary" icon="fas fa-plus">
                    <span class="hidden sm:inline">Upload RTK</span>
                    <span class="sm:hidden">Upload</span>
                </x-button>
            </x-slot>

            <x-slot name="filter_inputs">
                <!-- Status Verifikasi -->
                <div class="w-full sm:w-44 lg:flex-1">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Status Verifikasi</label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="status_verification" class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua</option>
                            @foreach (\Modules\RTK\Enums\RTKStatusVerification::cases() as $statusVerifikasi)
                                <option value="{{ $statusVerifikasi->value }}" @selected(request('status_verification') === $statusVerifikasi->value)>
                                    {{ $statusVerifikasi->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Status Dokumen -->
                <div class="w-full sm:w-44 lg:flex-1">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Status Dokumen</label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="status_document" class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua</option>
                            @foreach (\Modules\RTK\Enums\StatusDocument::cases() as $statusDocument)
                                <option value="{{ $statusDocument->value }}" @selected(request('status_document') === $statusDocument->value)>
                                    {{ $statusDocument->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- RTK Acuan -->
                <div class="w-full sm:w-40 lg:w-36">
                    <label class="block text-xs font-medium text-slate-500 mb-1">RTK Acuan</label>
                    <div class="relative">
                        <i class="fas fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select name="acuan" class="pl-9 pr-3 py-2.5 w-full rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua</option>
                            <option value="1" @selected(request('acuan') === '1')>Ya (Acuan)</option>
                            <option value="0" @selected(request('acuan') === '0')>Tidak</option>
                        </select>
                    </div>
                </div>

                <!-- Per Page -->
                <div class="w-full sm:w-36 lg:w-32">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Data per Halaman</label>
                    <select name="per_page" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ([10, 20, 50, 100] as $page)
                            <option value="{{ $page }}" {{ request('per_page') == $page ? 'selected' : '' }}>{{ $page }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pencarian -->
                <div class="w-full sm:flex-1 lg:flex-[1.5]">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Pencarian</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama RTK..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </x-slot>

            <x-table.table plain>
                <thead>
                    <tr>
                        <x-table.th>No.</x-table.th>
                        <x-table.th>Nama Dokumen</x-table.th>
                        <x-table.th>Periode Berlaku</x-table.th>
                        <x-table.th>Status Verifikasi</x-table.th>
                        <x-table.th>Status Dokumen Berlaku</x-table.th>
                        <x-table.th>RTK Acuan</x-table.th>
                        <x-table.th align="center">Aksi</x-table.th>
                    </tr>
                </thead>

                <tbody id="admin-table-body" class="divide-y divide-slate-200">
                    @forelse ($rtkns as $key => $rtkn)
                        <tr class="hover:bg-slate-50 transition">
                            <x-table.td>
                                <span class="text-slate-600">{{ $key + $rtkns->firstItem() }}</span>
                            </x-table.td>
                            <x-table.td>
                                <span class="text-slate-600">{{ $rtkn->name }}</span>
                            </x-table.td>
                            <x-table.td>
                                <span class="text-slate-600">{{ $rtkn->start_date }} - {{ $rtkn->end_date }}</span>
                            </x-table.td>
                            <x-table.td>
                                <x-badge color="{{ $rtkn->status_verification === \Modules\RTK\Enums\RTKStatusVerification::APPROVED ? 'success' : ($rtkn->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING ? 'indigo' : 'red') }}" :text="$rtkn->status_verification->label()" />
                            </x-table.td>
                            <x-table.td>
                                <x-badge color="{{ $rtkn->status_document === \Modules\RTK\Enums\StatusDocument::VALID ? 'success' : ($rtkn->status_document === \Modules\RTK\Enums\StatusDocument::EXPIRED ? 'red' : 'slate') }}" :text="$rtkn->status_document->label()" />
                            </x-table.td>
                            <x-table.td>
                                @if ($rtkn->is_active)
                                    <x-badge color="success" text="Ya" />
                                @else
                                    <x-badge color="slate" text="Tidak" />
                                @endif
                            </x-table.td>
                            <x-table.td align="center">
                                    <x-table.action>
                                        {{-- Step 1: Approve verifikasi — muncul kalau PENDING + is_active --}}
                                        @if($rtkn->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING && $rtkn->is_active)
                                            <li class="mb-2">
                                                <span class="px-3 py-2 text-center text-xs text-gray-400">Status Verifikasi</span>
                                                <div class="h-px my-1 -mx-1 bg-neutral-200"></div>
                                                <button type="button" x-data
                                                    @click="$dispatch('open-modal', 'approve-rtkn-{{ $rtkn->id }}')"
                                                    class="w-full px-3 py-2 cursor-pointer text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                                    Setujui Verifikasi
                                                </button>

                                                <x-modal name="approve-rtkn-{{ $rtkn->id }}" title="Konfirmasi Persetujuan RTKN"
                                                    maxWidth="sm:max-w-md">
                                                    <div class="p-6 text-center">
                                                        <div class="flex justify-center mb-4">
                                                            <div
                                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100">
                                                                <svg class="w-6 h-6 text-green-600"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Setujui Verifikasi
                                                            RTKN</h3>
                                                        <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menyetujui
                                                            verifikasi dokumen berikut?</p>
                                                        <div
                                                            class="bg-gray-50 border rounded-md p-3 text-sm text-gray-600 mb-6">
                                                            <p class="font-medium">{{ $rtkn->name }}</p>
                                                            <p class="text-xs text-gray-500">Periode {{ $rtkn->start_date }} -
                                                                {{ $rtkn->end_date }}</p>
                                                        </div>
                                                        <form
                                                            action="{{ route('admin-pusat.rtkn.approve-verification', $rtkn->id) }}"
                                                            method="POST" class="flex justify-center gap-3">
                                                            @csrf
                                                            <button type="submit"
                                                                class="px-4 py-2 cursor-pointer text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                                                Ya, Setujui
                                                            </button>
                                                            <button type="button"
                                                                @click="$dispatch('close-modal', 'approve-rtkn-{{ $rtkn->id }}')"
                                                                class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                                                Batal
                                                            </button>
                                                        </form>
                                                    </div>
                                                </x-modal>
                                            </li>
                                        @endif

                                        {{-- Step 2: Approve dokumen — muncul kalau status_verification APPROVED +
                                        status_document NA --}}
                                        @if(
                                                $rtkn->status_verification === \Modules\RTK\Enums\RTKStatusVerification::APPROVED &&
                                                $rtkn->status_document === \Modules\RTK\Enums\StatusDocument::NA &&
                                                $rtkn->is_active
                                            )
                                            <li class="mb-2">
                                                <span class="px-3 py-2 text-center text-xs text-gray-400">Status Dokumen</span>
                                                <div class="h-px my-1 -mx-1 bg-neutral-200"></div>
                                                <button type="button" x-data
                                                    @click="$dispatch('open-modal', 'approve-doc-rtkn-{{ $rtkn->id }}')"
                                                    class="w-full px-3 py-2 cursor-pointer text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                                    Setujui Dokumen
                                                </button>

                                                <x-modal name="approve-doc-rtkn-{{ $rtkn->id }}"
                                                    title="Konfirmasi Persetujuan Dokumen" maxWidth="sm:max-w-md">
                                                    <div class="p-6 text-center">
                                                        <div class="flex justify-center mb-4">
                                                            <div
                                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                                                                <svg class="w-6 h-6 text-blue-600"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Setujui Dokumen RTK
                                                        </h3>
                                                        <p class="text-sm text-gray-500 mb-4">
                                                            Dengan menyetujui dokumen ini, RTK akan menjadi
                                                            <strong>berlaku</strong>.
                                                        </p>

                                                        {{-- Warning kalau ada RTK berlaku --}}
                                                        @if($rtknBerlaku ?? false)
                                                            <div
                                                                class="bg-amber-50 border border-amber-200 rounded-md p-3 text-sm text-amber-700 mb-4 text-left">
                                                                <p class="font-medium">⚠ Perhatian</p>
                                                                <p class="text-xs mt-1">RTK <strong>{{ $rtknBerlaku->name }}</strong>
                                                                    yang sedang berlaku akan otomatis diubah menjadi EXPIRED.</p>
                                                            </div>
                                                        @endif

                                                        <div
                                                            class="bg-gray-50 border rounded-md p-3 text-sm text-gray-600 mb-6">
                                                            <p class="font-medium">{{ $rtkn->name }}</p>
                                                            <p class="text-xs text-gray-500">Periode {{ $rtkn->start_date }} -
                                                                {{ $rtkn->end_date }}</p>
                                                        </div>
                                                        <form action="{{ route('admin-pusat.rtkn.approve-document', $rtkn->id) }}" method="POST" class="flex justify-center gap-3">
                                                            @csrf
                                                            <button type="submit"
                                                                class="px-4 py-2 cursor-pointer text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                                                Ya, Setujui
                                                            </button>
                                                            <button type="button"
                                                                @click="$dispatch('close-modal', 'approve-doc-rtkn-{{ $rtkn->id }}')"
                                                                class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                                                Batal
                                                            </button>
                                                        </form>
                                                    </div>
                                                </x-modal>
                                            </li>
                                        @endif

                                        {{-- Tolak — muncul kalau PENDING atau APPROVED tapi belum VALID, dan is_active --}}
                                        @if(
                                                $rtkn->is_active && 
                                                $rtkn->status_document !== \Modules\RTK\Enums\StatusDocument::VALID && 
                                                $rtkn->status_verification !== \Modules\RTK\Enums\RTKStatusVerification::REJECTED
                                            )
                                            <li class="mb-2">
                                                <button type="button" x-data
                                                    @click="$dispatch('open-modal', 'reject-rtkn-{{ $rtkn->id }}')"
                                                    class="w-full px-3 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                                    Tolak
                                                </button>

                                                <x-modal name="reject-rtkn-{{ $rtkn->id }}" title="Konfirmasi Penolakan RTKN"
                                                    maxWidth="sm:max-w-xl">
                                                    <div class="p-6">
                                                        <div class="flex justify-center mb-4">
                                                            <div
                                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                                                                <svg class="w-6 h-6 text-red-600"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">Tolak
                                                            Dokumen RTK</h3>
                                                        <p class="text-sm text-gray-500 text-center mb-6">Silakan berikan alasan
                                                            penolakan dokumen berikut.</p>
                                                        <div
                                                            class="bg-gray-50 border rounded-md p-3 text-sm text-gray-600 mb-5">
                                                            <p class="font-medium">{{ $rtkn->name }}</p>
                                                            <p class="text-xs text-gray-500">Periode {{ $rtkn->start_date }} -
                                                                {{ $rtkn->end_date }}</p>
                                                        </div>
                                                        <form action="{{ route('admin-pusat.rtkn.reject-rtkn', $rtkn->id) }}"
                                                            method="POST" class="space-y-4">
                                                            @csrf
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-1">Alasan
                                                                    Penolakan</label>
                                                                <textarea name="reason" rows="3" required
                                                                    class="w-full px-3 py-2 text-sm border rounded-md focus:ring focus:ring-red-200 focus:border-red-400"
                                                                    placeholder="Contoh: Dokumen belum sesuai format yang ditetapkan"></textarea>
                                                            </div>
                                                            <div class="flex justify-end gap-3 pt-2">
                                                                <button type="button"
                                                                    @click="$dispatch('close-modal', 'reject-rtkn-{{ $rtkn->id }}')"
                                                                    class="px-4 py-2 cursor-pointer text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                                                                    Batal
                                                                </button>
                                                                <button type="submit"
                                                                    class="px-4 py-2 cursor-pointer text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                                                    Ya, Tolak
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </x-modal>
                                            </li>
                                            <div class="h-px my-1 -mx-1 bg-neutral-500"></div>
                                        @endif

                                        {{-- Badge RTK Berlaku --}}
                                        @if($rtkn->is_berlaku)
                                            <li>
                                                <span
                                                    class="inline-flex items-center gap-1 w-full justify-center px-3 py-2 text-sm font-medium text-emerald-700 bg-emerald-100 rounded">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    RTKN Berlaku
                                                </span>
                                            </li>
                                        @endif
                                        
                                        @if(
                                            (
                                                $rtkn->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING &&
                                                $rtkn->status_document === \Modules\RTK\Enums\StatusDocument::NA
                                            )
                                            ||
                                            (
                                                $rtkn->status_verification === \Modules\RTK\Enums\RTKStatusVerification::APPROVED &&
                                                $rtkn->status_document === \Modules\RTK\Enums\StatusDocument::NA
                                            )
                                            ||
                                            (
                                                $rtkn->status_verification === \Modules\RTK\Enums\RTKStatusVerification::REJECTED &&
                                                $rtkn->is_active  
                                                /* is_active true = masih bisa edit */
                                            )
                                        )
                                            <li class="mb-2">
                                                <a href="{{ route('admin-pusat.rtkn.edit', $rtkn->id) }}"
                                                    class="inline-flex items-center cursor-pointer w-full p-2 hover:bg-slate-100 rounded text-sm">
                                                    Edit RTK
                                                </a>
                                            </li>
                                        @endif

                                        <li>
                                            <a href="{{ Storage::url($rtkn->document_path) }}"
                                                download="{{ $rtkn->name }}"
                                                class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">Download</a>
                                        </li>

                                        <li class="mb-2">
                                            <button type="button" x-data
                                                @click="$dispatch('open-modal', 'open-document-rtkn-{{ $rtkn->id }}')"
                                                class="inline-flex items-center cursor-pointer w-full p-2 hover:bg-slate-100 rounded">
                                                Preview Dokumen RTKN
                                            </button>

                                            <x-modal name="open-document-rtkn-{{ $rtkn->id }}"
                                                title="Pratinjau Dokumen Saat Ini" maxWidth="sm:max-w-2xl">
                                                <h1>{{ $rtkn->name }}</h1>
                                                <div class="border border-gray-300 rounded-md overflow-hidden">
                                                    @if ($rtkn->document_path && Storage::disk('public')->exists($rtkn->document_path))
                                                        <iframe src="{{ Storage::url($rtkn->document_path) }}"
                                                            class="w-full min-h-[500px] rounded-md border"></iframe>
                                                    @else
                                                        <div
                                                            class="flex items-center justify-center min-h-[500px] text-gray-400 border rounded-md">
                                                            Tidak ada dokumen tersimpan
                                                        </div>
                                                    @endif
                                                </div>
                                                <x-slot:footer>
                                                    <button
                                                        @click="$dispatch('close-modal', 'open-document-rtkn-{{ $rtkn->id }}')"
                                                        class="inline-flex items-center justify-center px-4 cursor-pointer py-2 text-sm font-medium tracking-wide transition-colors duration-100 rounded-md text-neutral-500 bg-neutral-50 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-100 hover:text-neutral-600 hover:bg-neutral-100">
                                                        Close
                                                    </button>
                                                </x-slot:footer>
                                            </x-modal>
                                        </li>
                                        @if ($rtkn->status_verification === \Modules\RTK\Enums\RTKStatusVerification::PENDING && $rtkn->status_document === \Modules\RTK\Enums\StatusDocument::NA)
                                            <li>
                                                <div
                                                    class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded">
                                                    <x-modal-delete :id="$rtkn->id" message="Are you sure delete RTKN"
                                                    :item-name="$rtkn->name" :route="route('admin-pusat.rtkn.destroy', $rtkn->id)" />
                                                </div>
                                            </li>
                                        @endif
                                    </x-table.action>
                                </x-table.td>
                            </tr>
                        @empty
                            <tr class="">
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-500">Tidak ada data Rekapitulasi Rencana Tenaga Kerja
                                        Nasional
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-table.table>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $rtkns->links() }}
            </div>
        </x-dashboard::filter-card>
    </div>

    @push('scripts')
    @endpush
</x-dashboard::layouts.dashboard>