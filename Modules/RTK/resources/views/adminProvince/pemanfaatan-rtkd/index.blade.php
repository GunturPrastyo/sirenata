<x-dashboard::layouts.dashboard title="Pemanfaatan RTKD">
    <div class="p-2 sm:p-6">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin-province.dashboard') }}"
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
                        <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Pemanfaatan RTKD</span>
                    </div>
                </li>
            </ol>
        </nav>



        {{-- Main Content --}}
        <div class="space-y-6">
            {{-- Data Table Section --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-base font-semibold text-slate-800">Status Pemanfaatan Rencana Tenaga Kerja Daerah</h2>
                    @if(!$submission && $activePeriod)
                        <a href="{{ route('admin-province.pemanfaatan-rtkd.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Isi Kuesioner
                        </a>
                    @endif
                </div>
                
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-sm border-b border-slate-200">
                                <th class="px-6 py-3 font-medium">Periode</th>
                                <th class="px-6 py-3 font-medium">Tanggal Pengisian</th>
                                <th class="px-6 py-3 font-medium">Status Verifikasi</th>
                                <th class="px-6 py-3 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @if (!$activePeriod)
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                        Admin Pusat belum membuka periode survei pemanfaatan RTKD saat ini. Silakan kembali lagi nanti.
                                    </td>
                                </tr>
                            @elseif(!$submission)
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                        Belum ada kuesioner yang diisi untuk periode aktif.
                                    </td>
                                </tr>
                            @else
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-slate-900">{{ $activePeriod->nama }}</div>
                                        <div class="text-xs text-slate-500">Batas: {{ $activePeriod->tanggal_selesai ? $activePeriod->tanggal_selesai->format('d M Y') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $submission->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($submission->status_verifikasi === 'verified')
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                Sudah Disetujui
                                            </span>
                                        @elseif($submission->status_verifikasi === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                Revisi Diperlukan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                Menunggu Verifikasi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @php
                                            $hasNotes = false;
                                            if (is_array($submission->field_verifications)) {
                                                foreach ($submission->field_verifications as $v) {
                                                    if (!empty($v['catatan'])) {
                                                        $hasNotes = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        
                                        @if($submission->status_verifikasi === 'pending' && !$hasNotes)
                                            <a href="{{ route('admin-province.pemanfaatan-rtkd.edit', $submission->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition shadow-sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                Edit / Isi Ulang
                                            </a>
                                        @elseif($submission->status_verifikasi === 'rejected')
                                            <a href="{{ route('admin-province.pemanfaatan-rtkd.edit', $submission->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition shadow-sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                Revisi
                                            </a>
                                        @else
                                            <span class="text-sm text-slate-400 italic">Kuesioner Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Detail Data Kuesioner (Read-Only) --}}
            @if($submission)
                <div class="mt-8 space-y-6">
                    <h2 class="text-lg font-bold text-slate-800">Detail Kuesioner yang Diisi</h2>
                    
                    {{-- 1. Kepemilikan RTKD --}}
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                            <h3 class="text-base font-semibold text-slate-800">1. Kepemilikan Dokumen RTK Provinsi</h3>
                            @if(isset($submission->field_verifications['q1_punya_rtkd']) && $submission->field_verifications['q1_punya_rtkd']['status'] === 'rejected')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi Diminta</span>
                            @elseif(isset($submission->field_verifications['q1_punya_rtkd']) && $submission->field_verifications['q1_punya_rtkd']['status'] === 'verified')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                            @endif
                        </div>
                        <div class="p-4">
                            @if(isset($submission->field_verifications['q1_punya_rtkd']) && $submission->field_verifications['q1_punya_rtkd']['status'] === 'rejected')
                                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
                                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div><span class="font-semibold block">Catatan Verifikator Pusat:</span>{{ $submission->field_verifications['q1_punya_rtkd']['catatan'] }}</div>
                                </div>
                            @endif
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
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 1B. Alasan Tidak Punya RTKD --}}
                    @if($submission->q1_punya_rtkd === 'tidak')
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                                <h3 class="text-base font-semibold text-slate-800">Alasan Tidak Memiliki RTKD</h3>
                                @if(isset($submission->field_verifications['alasan_tidak_punya']) && $submission->field_verifications['alasan_tidak_punya']['status'] === 'rejected')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi Diminta</span>
                                @elseif(isset($submission->field_verifications['alasan_tidak_punya']) && $submission->field_verifications['alasan_tidak_punya']['status'] === 'verified')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                                @endif
                            </div>
                            <div class="p-4">
                                @if(isset($submission->field_verifications['alasan_tidak_punya']) && $submission->field_verifications['alasan_tidak_punya']['status'] === 'rejected')
                                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
                                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div><span class="font-semibold block">Catatan Verifikator Pusat:</span>{{ $submission->field_verifications['alasan_tidak_punya']['catatan'] }}</div>
                                    </div>
                                @endif
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
                        </div>
                    @endif

                    {{-- 2. Pemanfaatan Dokumen --}}
                    @if($submission->q1_punya_rtkd === 'ya')
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                                <h3 class="text-base font-semibold text-slate-800">2. Menjadi Acuan Perencanaan Pembangunan</h3>
                                @if(isset($submission->field_verifications['q2_jadi_acuan']) && $submission->field_verifications['q2_jadi_acuan']['status'] === 'rejected')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi Diminta</span>
                                @elseif(isset($submission->field_verifications['q2_jadi_acuan']) && $submission->field_verifications['q2_jadi_acuan']['status'] === 'verified')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                                @endif
                            </div>
                            <div class="p-4">
                                @if(isset($submission->field_verifications['q2_jadi_acuan']) && $submission->field_verifications['q2_jadi_acuan']['status'] === 'rejected')
                                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
                                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div><span class="font-semibold block">Catatan Verifikator Pusat:</span>{{ $submission->field_verifications['q2_jadi_acuan']['catatan'] }}</div>
                                    </div>
                                @endif
                                <div class="flex items-start gap-4">
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
                            </div>
                        </div>

                        {{-- JIKA JADI ACUAN --}}
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
                                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
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
                                        @if(isset($submission->field_verifications[$fieldKey]) && $submission->field_verifications[$fieldKey]['status'] === 'rejected')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi Diminta</span>
                                        @elseif(isset($submission->field_verifications[$fieldKey]) && $submission->field_verifications[$fieldKey]['status'] === 'verified')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                                        @endif
                                    </div>
                                    <div class="p-4 space-y-4">
                                        @if(isset($submission->field_verifications[$fieldKey]) && $submission->field_verifications[$fieldKey]['status'] === 'rejected')
                                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
                                                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <div><span class="font-semibold block">Catatan Verifikator Pusat:</span>{{ $submission->field_verifications[$fieldKey]['catatan'] }}</div>
                                            </div>
                                        @endif

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
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        {{-- JIKA BELUM ACUAN --}}
                        @else
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                                <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                                    <h3 class="text-base font-semibold text-slate-800">Alasan Belum Menjadi Acuan</h3>
                                    @if(isset($submission->field_verifications['alasan_belum_acuan']) && $submission->field_verifications['alasan_belum_acuan']['status'] === 'rejected')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi Diminta</span>
                                    @elseif(isset($submission->field_verifications['alasan_belum_acuan']) && $submission->field_verifications['alasan_belum_acuan']['status'] === 'verified')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    @if(isset($submission->field_verifications['alasan_belum_acuan']) && $submission->field_verifications['alasan_belum_acuan']['status'] === 'rejected')
                                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
                                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <div><span class="font-semibold block">Catatan Verifikator Pusat:</span>{{ $submission->field_verifications['alasan_belum_acuan']['catatan'] }}</div>
                                        </div>
                                    @endif
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
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-dashboard::layouts.dashboard>
