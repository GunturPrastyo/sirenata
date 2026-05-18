<x-dashboard::layouts.dashboard title="Detail Kuesioner Pemanfaatan RTKD">
    <div class="p-2 sm:p-6" x-data="verifikasiForm()">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-pusat.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('admin-pusat.hasil-pemanfaatan-rtkd.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Hasil Pemanfaatan RTKD</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Detail & Verifikasi</span>
                    </div>
                </li>
            </ol>
        </nav>



        <form action="{{ route('admin-pusat.hasil-pemanfaatan-rtkd.verify', $submission->id) }}" method="POST" id="form-verifikasi" class="space-y-6">
            @csrf
            @method('PATCH')
                
            {{-- Header Card --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10">
                    <svg class="w-24 h-24 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700">
                            {{ $submission->period->nama ?? 'Periode Tidak Diketahui' }}
                        </span>
                        @if($submission->status_verifikasi === 'verified')
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Telah Disetujui Sepenuhnya</span>
                        @elseif($submission->status_verifikasi === 'rejected')
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Membutuhkan Revisi Provinsi</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Menunggu Verifikasi Anda</span>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ $submission->user->name ?? 'Provinsi Unknown' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">Dikirim pada: {{ $submission->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>

            {{-- 1. Kepemilikan RTKD --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" :class="{ 'border-emerald-500 ring-1 ring-emerald-500': fields.q1_punya_rtkd.status === 'verified', 'border-red-500 ring-1 ring-red-500': fields.q1_punya_rtkd.status === 'rejected' }">
                <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h3 class="text-base font-semibold text-slate-800">1. Kepemilikan Dokumen RTK Provinsi</h3>
                    <span x-show="fields.q1_punya_rtkd.status === 'verified'" class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                    <span x-show="fields.q1_punya_rtkd.status === 'rejected'" class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi</span>
                </div>
                <div class="p-4 flex flex-col md:flex-row gap-6">
                    <div class="flex-1">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $submission->q1_punya_rtkd === 'ya' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                @if($submission->q1_punya_rtkd === 'ya')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-slate-500 font-medium">Apakah Memiliki RTKD?</p>
                                <p class="text-lg font-bold {{ $submission->q1_punya_rtkd === 'ya' ? 'text-emerald-700' : 'text-red-700' }}">
                                    {{ $submission->q1_punya_rtkd === 'ya' ? 'YA, MEMILIKI' : 'TIDAK MEMILIKI' }}
                                </p>
                                
                                @if($submission->q1_punya_rtkd === 'ya')
                                    <div class="mt-4 grid grid-cols-2 gap-4">
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                                            <p class="text-xs text-slate-500 mb-1">Masa Berlaku Dokumen</p>
                                            <p class="text-sm font-semibold text-slate-800">{{ $submission->tahun_dari }} - {{ $submission->tahun_sampai }}</p>
                                        </div>
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                                            <p class="text-xs text-slate-500 mb-1">Status Dokumen</p>
                                            <p class="text-sm font-semibold text-indigo-600 underline">
                                                <a href="{{ $submission->rtkDocument ? route('admin-pusat.rtkd.show-province', $submission->rtkDocument->province_code) : '#' }}" target="_blank">Lihat Dokumen Referensi</a>
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Verifikasi Inline --}}
                    <div class="w-full md:w-1/3 border-t md:border-t-0 md:border-l border-slate-200 pt-4 md:pt-0 md:pl-6">
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition border" :class="fields.q1_punya_rtkd.status === 'verified' ? 'border-emerald-200 bg-emerald-50' : 'border-transparent'">
                                <input type="radio" name="verifications[q1_punya_rtkd][status]" value="verified" x-model="fields.q1_punya_rtkd.status" class="text-emerald-500 focus:ring-emerald-500">
                                <span class="text-sm font-semibold text-slate-700">Setujui</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition border" :class="fields.q1_punya_rtkd.status === 'rejected' ? 'border-red-200 bg-red-50' : 'border-transparent'">
                                <input type="radio" name="verifications[q1_punya_rtkd][status]" value="rejected" x-model="fields.q1_punya_rtkd.status" class="text-red-500 focus:ring-red-500">
                                <span class="text-sm font-semibold text-slate-700">Minta Revisi</span>
                            </label>
                            <div x-show="fields.q1_punya_rtkd.status === 'rejected'" class="mt-2">
                                <textarea name="verifications[q1_punya_rtkd][catatan]" x-model="fields.q1_punya_rtkd.catatan" class="w-full text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" rows="2" placeholder="Tulis catatan revisi..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 1B. Alasan Tidak Punya RTKD --}}
            @if($submission->q1_punya_rtkd === 'tidak')
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" :class="{ 'border-emerald-500 ring-1 ring-emerald-500': fields.alasan_tidak_punya.status === 'verified', 'border-red-500 ring-1 ring-red-500': fields.alasan_tidak_punya.status === 'rejected' }">
                    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                        <h3 class="text-base font-semibold text-slate-800">Alasan Tidak Memiliki RTKD</h3>
                        <span x-show="fields.alasan_tidak_punya.status === 'verified'" class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                        <span x-show="fields.alasan_tidak_punya.status === 'rejected'" class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi</span>
                    </div>
                    <div class="p-4 flex flex-col md:flex-row gap-6">
                        <div class="flex-1">
                            <ul class="list-disc list-inside text-sm text-slate-700 space-y-1">
                                @if(is_array($submission->alasan_tidak_punya))
                                    @foreach($submission->alasan_tidak_punya as $alasan)
                                        <li>
                                            {{ $alasan['alasan'] }}
                                            @if($alasan['alasan'] === 'Lainnya' && !empty($alasan['keterangan_lainnya']))
                                                (<i>{{ $alasan['keterangan_lainnya'] }}</i>)
                                            @endif
                                        </li>
                                    @endforeach
                                @else
                                    <li class="text-slate-400 italic">Tidak ada data alasan.</li>
                                @endif
                            </ul>
                        </div>
                        <div class="w-full md:w-1/3 border-t md:border-t-0 md:border-l border-slate-200 pt-4 md:pt-0 md:pl-6">
                            <div class="space-y-3">
                                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition border" :class="fields.alasan_tidak_punya.status === 'verified' ? 'border-emerald-200 bg-emerald-50' : 'border-transparent'">
                                    <input type="radio" name="verifications[alasan_tidak_punya][status]" value="verified" x-model="fields.alasan_tidak_punya.status" class="text-emerald-500 focus:ring-emerald-500">
                                    <span class="text-sm font-semibold text-slate-700">Setujui</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition border" :class="fields.alasan_tidak_punya.status === 'rejected' ? 'border-red-200 bg-red-50' : 'border-transparent'">
                                    <input type="radio" name="verifications[alasan_tidak_punya][status]" value="rejected" x-model="fields.alasan_tidak_punya.status" class="text-red-500 focus:ring-red-500">
                                    <span class="text-sm font-semibold text-slate-700">Minta Revisi</span>
                                </label>
                                <div x-show="fields.alasan_tidak_punya.status === 'rejected'" class="mt-2">
                                    <textarea name="verifications[alasan_tidak_punya][catatan]" x-model="fields.alasan_tidak_punya.catatan" class="w-full text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" rows="2" placeholder="Tulis catatan revisi..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 2. Pemanfaatan Dokumen (Hanya jika punya RTKD) --}}
            @if($submission->q1_punya_rtkd === 'ya')
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" :class="{ 'border-emerald-500 ring-1 ring-emerald-500': fields.q2_jadi_acuan.status === 'verified', 'border-red-500 ring-1 ring-red-500': fields.q2_jadi_acuan.status === 'rejected' }">
                    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                        <h3 class="text-base font-semibold text-slate-800">2. Menjadi Acuan Perencanaan Pembangunan</h3>
                        <span x-show="fields.q2_jadi_acuan.status === 'verified'" class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                        <span x-show="fields.q2_jadi_acuan.status === 'rejected'" class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi</span>
                    </div>
                    <div class="p-4 flex flex-col md:flex-row gap-6">
                        <div class="flex-1 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $submission->q2_jadi_acuan === 'ya' ? 'bg-indigo-100 text-indigo-600' : 'bg-amber-100 text-amber-600' }}">
                                @if($submission->q2_jadi_acuan === 'ya')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 font-medium">Apakah telah dijadikan acuan?</p>
                                <p class="text-lg font-bold {{ $submission->q2_jadi_acuan === 'ya' ? 'text-indigo-700' : 'text-amber-700' }}">
                                    {{ $submission->q2_jadi_acuan === 'ya' ? 'YA, MENJADI ACUAN' : 'BELUM MENJADI ACUAN' }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 border-t md:border-t-0 md:border-l border-slate-200 pt-4 md:pt-0 md:pl-6">
                            <div class="space-y-3">
                                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition border" :class="fields.q2_jadi_acuan.status === 'verified' ? 'border-emerald-200 bg-emerald-50' : 'border-transparent'">
                                    <input type="radio" name="verifications[q2_jadi_acuan][status]" value="verified" x-model="fields.q2_jadi_acuan.status" class="text-emerald-500 focus:ring-emerald-500">
                                    <span class="text-sm font-semibold text-slate-700">Setujui</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition border" :class="fields.q2_jadi_acuan.status === 'rejected' ? 'border-red-200 bg-red-50' : 'border-transparent'">
                                    <input type="radio" name="verifications[q2_jadi_acuan][status]" value="rejected" x-model="fields.q2_jadi_acuan.status" class="text-red-500 focus:ring-red-500">
                                    <span class="text-sm font-semibold text-slate-700">Minta Revisi</span>
                                </label>
                                <div x-show="fields.q2_jadi_acuan.status === 'rejected'" class="mt-2">
                                    <textarea name="verifications[q2_jadi_acuan][catatan]" x-model="fields.q2_jadi_acuan.catatan" class="w-full text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" rows="2" placeholder="Tulis catatan revisi..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- JIKA JADI ACUAN: Grouped per Document Type --}}
                @if($submission->q2_jadi_acuan === 'ya')
                    
                    @php
                        $docLabels = ['rpjmd' => 'RPJMD', 'renstra' => 'RENSTRA Disnaker', 'lainnya' => 'Dokumen Lainnya'];
                        $docDescriptions = ['rpjmd' => 'Rencana Pembangunan Jangka Menengah Daerah', 'renstra' => 'Rencana Strategis Perangkat Daerah', 'lainnya' => ''];
                        $submittedDocs = is_array($submission->dokumen_acuan) ? collect($submission->dokumen_acuan)->pluck('doc_type')->toArray() : [];
                        $komponenByDoc = [];
                        if (is_array($submission->komponen_acuan)) {
                            foreach ($submission->komponen_acuan as $k) {
                                $komponenByDoc[$k['doc_type']][] = $k;
                            }
                        }
                        $uploadsByDoc = [];
                        if (is_array($submission->dokumen_uploads)) {
                            foreach ($submission->dokumen_uploads as $u) {
                                $uploadsByDoc[$u['doc_type']] = $u;
                            }
                        }
                    @endphp

                    @foreach($submittedDocs as $docType)
                        @php
                            $fieldKey = 'dok_' . $docType;
                            $docLabel = $docLabels[$docType] ?? strtoupper($docType);
                            $docDesc = $docDescriptions[$docType] ?? '';
                            $docAcuanEntry = collect($submission->dokumen_acuan)->firstWhere('doc_type', $docType);
                            $kompList = $komponenByDoc[$docType] ?? [];
                            $uploadEntry = $uploadsByDoc[$docType] ?? null;
                        @endphp
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" :class="{ 'border-emerald-500 ring-1 ring-emerald-500': fields.{{ $fieldKey }}?.status === 'verified', 'border-red-500 ring-1 ring-red-500': fields.{{ $fieldKey }}?.status === 'rejected' }">
                            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                                <div>
                                    <h3 class="text-base font-semibold text-slate-800">
                                        {{ $docLabel }}
                                        @if($docDesc)
                                            <span class="text-sm font-normal text-slate-500">({{ $docDesc }})</span>
                                        @endif
                                    </h3>
                                    @if($docType === 'lainnya' && !empty($docAcuanEntry['nama_lainnya']))
                                        <p class="text-sm text-slate-500 mt-0.5">Nama Dokumen: <strong>{{ $docAcuanEntry['nama_lainnya'] }}</strong></p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span x-show="fields.{{ $fieldKey }}?.status === 'verified'" class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                                    <span x-show="fields.{{ $fieldKey }}?.status === 'rejected'" class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi</span>
                                </div>
                            </div>
                            <div class="p-0 flex flex-col md:flex-row">
                                <div class="flex-1 p-4 space-y-4">
                                    {{-- Upload File --}}
                                    @if($uploadEntry)
                                        <div>
                                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">📎 File Dokumen</p>
                                            <a href="{{ Storage::url($uploadEntry['file_path']) }}" target="_blank" class="inline-flex items-center gap-3 p-3 border border-indigo-200 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition group">
                                                <div class="w-10 h-10 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-indigo-800 group-hover:text-indigo-900">{{ $uploadEntry['original_name'] }}</p>
                                                    <p class="text-xs text-indigo-600">Klik untuk membuka</p>
                                                </div>
                                            </a>
                                        </div>
                                    @else
                                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700">
                                            ⚠️ Belum ada file yang diunggah untuk dokumen ini.
                                        </div>
                                    @endif

                                    {{-- Komponen RTKD --}}
                                    @if(count($kompList) > 0)
                                        <div>
                                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Komponen RTKD yang Diacu</p>
                                            <table class="min-w-full text-sm border border-slate-200 rounded-lg overflow-hidden">
                                                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left font-medium">Komponen</th>
                                                        <th class="px-4 py-2 text-left font-medium">Halaman</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 bg-white">
                                                    @foreach($kompList as $komp)
                                                        <tr>
                                                            <td class="px-4 py-2">
                                                                {{ $komp['komponen'] }}
                                                                @if($komp['komponen'] === 'Lainnya' && !empty($komp['keterangan_lainnya']))
                                                                    (<i>{{ $komp['keterangan_lainnya'] }}</i>)
                                                                @endif
                                                            </td>
                                                            <td class="px-4 py-2 text-slate-500">{{ $komp['halaman_acuan'] ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-sm text-slate-400 italic">Belum ada komponen yang dipilih.</p>
                                    @endif
                                </div>

                                {{-- Verifikasi Panel --}}
                                <div class="w-full md:w-1/3 border-t md:border-t-0 md:border-l border-slate-200 p-5 bg-slate-50 md:bg-transparent">
                                    <div class="space-y-3">
                                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition border" :class="fields.{{ $fieldKey }}?.status === 'verified' ? 'border-emerald-200 bg-emerald-50' : 'border-transparent'">
                                            <input type="radio" name="verifications[{{ $fieldKey }}][status]" value="verified" x-model="fields.{{ $fieldKey }}.status" class="text-emerald-500 focus:ring-emerald-500">
                                            <span class="text-sm font-semibold text-slate-700">Setujui</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition border" :class="fields.{{ $fieldKey }}?.status === 'rejected' ? 'border-red-200 bg-red-50' : 'border-transparent'">
                                            <input type="radio" name="verifications[{{ $fieldKey }}][status]" value="rejected" x-model="fields.{{ $fieldKey }}.status" class="text-red-500 focus:ring-red-500">
                                            <span class="text-sm font-semibold text-slate-700">Minta Revisi</span>
                                        </label>
                                        <div x-show="fields.{{ $fieldKey }}?.status === 'rejected'" class="mt-2">
                                            <textarea name="verifications[{{ $fieldKey }}][catatan]" x-model="fields.{{ $fieldKey }}.catatan" class="w-full text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" rows="2" placeholder="Tulis catatan revisi..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                {{-- JIKA BELUM ACUAN --}}
                @else
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" :class="{ 'border-emerald-500 ring-1 ring-emerald-500': fields.alasan_belum_acuan.status === 'verified', 'border-red-500 ring-1 ring-red-500': fields.alasan_belum_acuan.status === 'rejected' }">
                        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                            <h3 class="text-base font-semibold text-slate-800">Alasan Belum Menjadi Acuan</h3>
                            <span x-show="fields.alasan_belum_acuan.status === 'verified'" class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                            <span x-show="fields.alasan_belum_acuan.status === 'rejected'" class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi</span>
                        </div>
                        <div class="p-4 flex flex-col md:flex-row gap-6">
                            <div class="flex-1">
                                <ul class="list-disc list-inside text-sm text-slate-700 space-y-1">
                                    @if(is_array($submission->alasan_belum_acuan))
                                        @foreach($submission->alasan_belum_acuan as $alasan)
                                            <li>
                                                {{ $alasan['alasan'] }}
                                                @if($alasan['alasan'] === 'Lainnya' && !empty($alasan['keterangan_lainnya']))
                                                    (<i>{{ $alasan['keterangan_lainnya'] }}</i>)
                                                @endif
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="text-slate-400 italic">Tidak ada data alasan.</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="w-full md:w-1/3 border-t md:border-t-0 md:border-l border-slate-200 pt-4 md:pt-0 md:pl-6">
                                <div class="space-y-3">
                                    <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition border" :class="fields.alasan_belum_acuan.status === 'verified' ? 'border-emerald-200 bg-emerald-50' : 'border-transparent'">
                                        <input type="radio" name="verifications[alasan_belum_acuan][status]" value="verified" x-model="fields.alasan_belum_acuan.status" class="text-emerald-500 focus:ring-emerald-500">
                                        <span class="text-sm font-semibold text-slate-700">Setujui</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-slate-50 transition border" :class="fields.alasan_belum_acuan.status === 'rejected' ? 'border-red-200 bg-red-50' : 'border-transparent'">
                                        <input type="radio" name="verifications[alasan_belum_acuan][status]" value="rejected" x-model="fields.alasan_belum_acuan.status" class="text-red-500 focus:ring-red-500">
                                        <span class="text-sm font-semibold text-slate-700">Minta Revisi</span>
                                    </label>
                                    <div x-show="fields.alasan_belum_acuan.status === 'rejected'" class="mt-2">
                                        <textarea name="verifications[alasan_belum_acuan][catatan]" x-model="fields.alasan_belum_acuan.catatan" class="w-full text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" rows="2" placeholder="Tulis catatan revisi..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Tombol Aksi Akhir --}}
            <div class="bg-slate-800 rounded-xl p-4 shadow-lg sticky bottom-6 z-20 flex flex-col sm:flex-row items-center justify-between gap-4 mt-8">
                <div>
                    <h4 class="text-white font-semibold">Simpan Hasil Verifikasi</h4>
                    <p class="text-slate-400 text-sm mt-1" x-text="summaryText"></p>
                </div>
                <div class="flex gap-3 w-full sm:w-auto">
                    <button type="submit" name="final_action" value="reject" x-show="hasRejection" class="w-full sm:w-auto px-6 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Simpan & Kembalikan (Minta Revisi)
                    </button>
                    <button type="submit" name="final_action" value="verify" x-show="!hasRejection" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Setujui Seluruh Kuesioner
                    </button>
                </div>
            </div>

        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('verifikasiForm', () => {
                const oldVerifs = @json($submission->field_verifications ?? []);
                const submittedDocTypes = @json(is_array($submission->dokumen_acuan) ? collect($submission->dokumen_acuan)->pluck('doc_type')->toArray() : []);
                
                const initField = (key) => ({
                    status: oldVerifs[key]?.status || 'verified',
                    catatan: oldVerifs[key]?.catatan || ''
                });

                // Build dynamic fields for each submitted document type
                let dynamicFields = {};
                submittedDocTypes.forEach(dt => {
                    const key = 'dok_' + dt;
                    dynamicFields[key] = initField(key);
                });

                return {
                    fields: {
                        q1_punya_rtkd: initField('q1_punya_rtkd'),
                        alasan_tidak_punya: initField('alasan_tidak_punya'),
                        q2_jadi_acuan: initField('q2_jadi_acuan'),
                        alasan_belum_acuan: initField('alasan_belum_acuan'),
                        ...dynamicFields,
                    },
                    
                    get hasRejection() {
                        return Object.values(this.fields).some(f => f.status === 'rejected');
                    },

                    get summaryText() {
                        if (this.hasRejection) {
                            return 'Terdapat bagian yang ditolak. Sistem akan meminta provinsi melakukan revisi.';
                        }
                        return 'Seluruh bagian telah disetujui. Data kuesioner akan diverifikasi penuh.';
                    }
                }
            })
        })
    </script>
    @endpush
</x-dashboard::layouts.dashboard>
