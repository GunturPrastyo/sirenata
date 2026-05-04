<x-dashboard::layouts.dashboard title="Hasil Kuesioner Pemanfaatan RTKD">
    <div class="p-2 sm:p-6">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-pusat.dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Hasil Pemanfaatan RTKD</span>
                    </div>
                </li>
            </ol>
        </nav>



        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar Hasil Kuesioner</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Total: <span class="font-medium text-slate-700">{{ $submissions->total() }}</span> Entri
                    </p>
                </div>
                
                {{-- Form Filter --}}
                <form action="{{ route('admin-pusat.hasil-pemanfaatan-rtkd.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    {{-- Filter Periode --}}
                    <div class="w-full sm:w-auto">
                        <select name="period_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Semua Periode</option>
                            @foreach($periods as $period)
                                <option value="{{ $period->id }}" {{ $selectedPeriodId == $period->id ? 'selected' : '' }}>
                                    {{ $period->nama }} ({{ $period->tahun }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Punya RTKD --}}
                    <div class="w-full sm:w-auto">
                        <select name="q1_punya_rtkd" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Semua Kepemilikan</option>
                            <option value="ya" {{ request('q1_punya_rtkd') == 'ya' ? 'selected' : '' }}>Punya RTKD</option>
                            <option value="tidak" {{ request('q1_punya_rtkd') == 'tidak' ? 'selected' : '' }}>Tidak Punya</option>
                        </select>
                    </div>

                    {{-- Filter Jadi Acuan --}}
                    <div class="w-full sm:w-auto">
                        <select name="q2_jadi_acuan" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Semua Status Acuan</option>
                            <option value="ya" {{ request('q2_jadi_acuan') == 'ya' ? 'selected' : '' }}>Jadi Acuan</option>
                            <option value="tidak" {{ request('q2_jadi_acuan') == 'tidak' ? 'selected' : '' }}>Belum Acuan</option>
                        </select>
                    </div>

                    {{-- Search Provinsi --}}
                    <div class="w-full sm:w-auto relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Provinsi..." 
                            class="w-full pl-9 pr-3 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>
                    
                    <noscript><button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">Cari</button></noscript>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 border-b border-slate-200">
                        <tr class="text-slate-500 uppercase text-xs text-left">
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">Provinsi</th>
                            <th class="px-4 py-3">Tanggal Isi</th>
                            <th class="px-4 py-3 text-center">Punya RTKD</th>
                            <th class="px-4 py-3 text-center">Masa Berlaku</th>
                            <th class="px-4 py-3 text-center">Jadi Acuan</th>
                            <th class="px-4 py-3 text-center">Dok. Acuan</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($submissions as $key => $sub)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 text-center text-slate-600">
                                    {{ $key + $submissions->firstItem() }}
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-800">
                                    {{ $sub->user->name ?? 'Unknown' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $sub->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($sub->q1_punya_rtkd === 'ya')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">Ya</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Tidak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-slate-600">
                                    {{ $sub->tahun_dari ? $sub->tahun_dari . ' - ' . $sub->tahun_sampai : '—' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($sub->q2_jadi_acuan === 'ya')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">Ya</span>
                                    @elseif($sub->q2_jadi_acuan === 'tidak')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Belum</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($sub->dokumen_acuan && count($sub->dokumen_acuan) > 0)
                                        <div class="flex justify-center gap-1 flex-wrap">
                                            @foreach($sub->dokumen_acuan as $doc)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-200 text-slate-700">
                                                    {{ strtoupper($doc['doc_type']) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($sub->status_verifikasi === 'verified')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Disetujui
                                        </span>
                                    @elseif($sub->status_verifikasi === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Revisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 md:px-6 py-3 text-center">
                                    <x-table.action>
                                        <li>
                                            <a href="{{ route('admin-pusat.hasil-pemanfaatan-rtkd.show', $sub->id) }}" class="inline-flex items-center w-full p-2 hover:bg-slate-100 rounded text-indigo-600">
                                                Lihat & Verifikasi
                                            </a>
                                        </li>
                                    </x-table.action>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm text-slate-500">Belum ada data kuesioner yang sesuai dengan filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-200">
                {{ $submissions->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</x-dashboard::layouts.dashboard>
