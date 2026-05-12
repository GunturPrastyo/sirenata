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

        @if($activePeriod && !$activeSubmission)
            <div class="mb-6 p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-amber-800">Periode Survei Sedang Aktif</h3>
                        <p class="mt-1 text-sm text-amber-700">
                            Periode <span class="font-bold">{{ $activePeriod->nama }}</span> sedang berlangsung hingga
                            <span class="font-bold">{{ $activePeriod->tanggal_selesai ? $activePeriod->tanggal_selesai->format('d M Y') : '-' }}</span>.
                            Anda belum mengisi kuesioner pemanfaatan RTKD untuk periode ini. Silakan segera mengisi kuesioner.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Content --}}
        <div class="space-y-6">
            {{-- Data Table Section --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-base font-semibold text-slate-800">Status Pemanfaatan Rencana Tenaga Kerja Daerah</h2>
                    @if(!$activeSubmission && $activePeriod)
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
                                <th class="px-6 py-3 font-medium text-center">Status Periode</th>
                                <th class="px-6 py-3 font-medium">Tanggal Pengisian</th>
                                <th class="px-6 py-3 font-medium">Status Verifikasi</th>
                                <th class="px-6 py-3 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @if ($submissions->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                        @if($activePeriod)
                                            Belum ada kuesioner yang diisi untuk periode aktif. Silakan mengisi kuesioner untuk mulai melihat riwayat.
                                        @else
                                            Belum ada kuesioner yang pernah diisi sebelumnya.
                                        @endif
                                    </td>
                                </tr>
                            @else
                                @foreach($submissions as $sub)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-slate-900">{{ $sub->period->nama }}</div>
                                            <div class="text-xs text-slate-500">Batas: {{ $sub->period->tanggal_selesai ? $sub->period->tanggal_selesai->format('d M Y') : '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($sub->period->status === 'aktif')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                                    Ditutup
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">
                                            {{ $sub->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($sub->status_verifikasi === 'verified')
                                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    Sudah Disetujui
                                                </span>
                                            @elseif($sub->status_verifikasi === 'rejected')
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
                                                if (is_array($sub->field_verifications)) {
                                                    foreach ($sub->field_verifications as $v) {
                                                        if (!empty($v['catatan'])) {
                                                            $hasNotes = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            
                                            @if($sub->period->status === 'aktif' && $sub->status_verifikasi === 'pending' && !$hasNotes)
                                                <a href="{{ route('admin-province.pemanfaatan-rtkd.edit', $sub->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition shadow-sm">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    Edit
                                                </a>
                                            @elseif($sub->period->status === 'aktif' && $sub->status_verifikasi === 'rejected')
                                                <a href="{{ route('admin-province.pemanfaatan-rtkd.edit', $sub->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition shadow-sm">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                    Revisi
                                                </a>
                                            @else
                                                <span class="text-sm text-slate-400 italic">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Detail Data Kuesioner (Read-Only) --}}
            @if($detailSubmission)
                <div class="mt-8 space-y-6">
                    <h2 class="text-lg font-bold text-slate-800">Detail Kuesioner Terpilih (Periode Aktif)</h2>
                    
                    {{-- 1. Kepemilikan RTKD --}}
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                            <h3 class="text-base font-semibold text-slate-800">1. Kepemilikan Dokumen RTK Provinsi</h3>
                            @if(isset($detailSubmission->field_verifications['q1_punya_rtkd']) && $detailSubmission->field_verifications['q1_punya_rtkd']['status'] === 'rejected')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi Diminta</span>
                            @elseif(isset($detailSubmission->field_verifications['q1_punya_rtkd']) && $detailSubmission->field_verifications['q1_punya_rtkd']['status'] === 'verified')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                            @endif
                        </div>
                        <div class="p-4">
                            @if(isset($detailSubmission->field_verifications['q1_punya_rtkd']) && $detailSubmission->field_verifications['q1_punya_rtkd']['status'] === 'rejected')
                                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
                                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div><span class="font-semibold block">Catatan Verifikator Pusat:</span>{{ $detailSubmission->field_verifications['q1_punya_rtkd']['catatan'] }}</div>
                                </div>
                            @endif
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $detailSubmission->q1_punya_rtkd === 'ya' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                    @if($detailSubmission->q1_punya_rtkd === 'ya')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-slate-500 font-medium">Apakah Memiliki RTKD?</p>
                                    <p class="text-lg font-bold {{ $detailSubmission->q1_punya_rtkd === 'ya' ? 'text-emerald-700' : 'text-red-700' }}">
                                        {{ $detailSubmission->q1_punya_rtkd === 'ya' ? 'YA, MEMILIKI' : 'TIDAK MEMILIKI' }}
                                    </p>
                                    
                                    @if($detailSubmission->q1_punya_rtkd === 'ya')
                                        <div class="mt-4 grid grid-cols-2 gap-4">
                                            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                                                <p class="text-xs text-slate-500 mb-1">Masa Berlaku Dokumen</p>
                                                <p class="text-sm font-semibold text-slate-800">{{ $detailSubmission->tahun_dari }} - {{ $detailSubmission->tahun_sampai }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 1B. Alasan Tidak Punya RTKD --}}
                    @if($detailSubmission->q1_punya_rtkd === 'tidak')
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                                <h3 class="text-base font-semibold text-slate-800">Alasan Tidak Memiliki RTKD</h3>
                                @if(isset($detailSubmission->field_verifications['alasan_tidak_punya']) && $detailSubmission->field_verifications['alasan_tidak_punya']['status'] === 'rejected')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi Diminta</span>
                                @elseif(isset($detailSubmission->field_verifications['alasan_tidak_punya']) && $detailSubmission->field_verifications['alasan_tidak_punya']['status'] === 'verified')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                                @endif
                            </div>
                            <div class="p-4">
                                @if(isset($detailSubmission->field_verifications['alasan_tidak_punya']) && $detailSubmission->field_verifications['alasan_tidak_punya']['status'] === 'rejected')
                                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
                                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div><span class="font-semibold block">Catatan Verifikator Pusat:</span>{{ $detailSubmission->field_verifications['alasan_tidak_punya']['catatan'] }}</div>
                                    </div>
                                @endif
                                <ul class="list-disc list-inside text-sm text-slate-700 space-y-1">
                                    @if(is_array($detailSubmission->alasan_tidak_punya))
                                        @foreach($detailSubmission->alasan_tidak_punya as $alasan)
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
                    @if($detailSubmission->q1_punya_rtkd === 'ya')
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                                <h3 class="text-base font-semibold text-slate-800">2. Menjadi Acuan Perencanaan Pembangunan</h3>
                                @if(isset($detailSubmission->field_verifications['q2_jadi_acuan']) && $detailSubmission->field_verifications['q2_jadi_acuan']['status'] === 'rejected')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi Diminta</span>
                                @elseif(isset($detailSubmission->field_verifications['q2_jadi_acuan']) && $detailSubmission->field_verifications['q2_jadi_acuan']['status'] === 'verified')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                                @endif
                            </div>
                            <div class="p-4">
                                @if(isset($detailSubmission->field_verifications['q2_jadi_acuan']) && $detailSubmission->field_verifications['q2_jadi_acuan']['status'] === 'rejected')
                                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
                                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div><span class="font-semibold block">Catatan Verifikator Pusat:</span>{{ $detailSubmission->field_verifications['q2_jadi_acuan']['catatan'] }}</div>
                                    </div>
                                @endif
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $detailSubmission->q2_jadi_acuan === 'ya' ? 'bg-indigo-100 text-indigo-600' : 'bg-amber-100 text-amber-600' }}">
                                        @if($detailSubmission->q2_jadi_acuan === 'ya')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500 font-medium">Apakah telah dijadikan acuan?</p>
                                        <p class="text-lg font-bold {{ $detailSubmission->q2_jadi_acuan === 'ya' ? 'text-indigo-700' : 'text-amber-700' }}">
                                            {{ $detailSubmission->q2_jadi_acuan === 'ya' ? 'YA, MENJADI ACUAN' : 'BELUM MENJADI ACUAN' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- JIKA JADI ACUAN --}}
                        @if($detailSubmission->q2_jadi_acuan === 'ya')
                            @php
                                $docLabels = ['rpjmd' => 'RPJMD', 'renstra' => 'RENSTRA Disnaker', 'lainnya' => 'Dokumen Lainnya'];
                                $docDescriptions = ['rpjmd' => 'Rencana Pembangunan Jangka Menengah Daerah', 'renstra' => 'Rencana Strategis Perangkat Daerah', 'lainnya' => ''];
                                $submittedDocs = is_array($detailSubmission->dokumen_acuan) ? collect($detailSubmission->dokumen_acuan)->pluck('doc_type')->toArray() : [];
                                $komponenByDoc = [];
                                if (is_array($detailSubmission->komponen_acuan)) {
                                    foreach ($detailSubmission->komponen_acuan as $k) {
                                        $komponenByDoc[$k['doc_type']][] = $k;
                                    }
                                }
                                $uploadsByDoc = [];
                                if (is_array($detailSubmission->dokumen_uploads)) {
                                    foreach ($detailSubmission->dokumen_uploads as $u) {
                                        $uploadsByDoc[$u['doc_type']] = $u;
                                    }
                                }
                            @endphp

                            @foreach($submittedDocs as $docType)
                                @php
                                    $fieldKey = 'dok_' . $docType;
                                    $docLabel = $docLabels[$docType] ?? strtoupper($docType);
                                    $docDesc = $docDescriptions[$docType] ?? '';
                                    $docAcuanEntry = collect($detailSubmission->dokumen_acuan)->firstWhere('doc_type', $docType);
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
                                        @if(isset($detailSubmission->field_verifications[$fieldKey]) && $detailSubmission->field_verifications[$fieldKey]['status'] === 'rejected')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi Diminta</span>
                                        @elseif(isset($detailSubmission->field_verifications[$fieldKey]) && $detailSubmission->field_verifications[$fieldKey]['status'] === 'verified')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                                        @endif
                                    </div>
                                    <div class="p-4 space-y-4">
                                        @if(isset($detailSubmission->field_verifications[$fieldKey]) && $detailSubmission->field_verifications[$fieldKey]['status'] === 'rejected')
                                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
                                                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <div><span class="font-semibold block">Catatan Verifikator Pusat:</span>{{ $detailSubmission->field_verifications[$fieldKey]['catatan'] }}</div>
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
                                    @if(isset($detailSubmission->field_verifications['alasan_belum_acuan']) && $detailSubmission->field_verifications['alasan_belum_acuan']['status'] === 'rejected')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Revisi Diminta</span>
                                    @elseif(isset($detailSubmission->field_verifications['alasan_belum_acuan']) && $detailSubmission->field_verifications['alasan_belum_acuan']['status'] === 'verified')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    @if(isset($detailSubmission->field_verifications['alasan_belum_acuan']) && $detailSubmission->field_verifications['alasan_belum_acuan']['status'] === 'rejected')
                                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-start gap-2">
                                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <div><span class="font-semibold block">Catatan Verifikator Pusat:</span>{{ $detailSubmission->field_verifications['alasan_belum_acuan']['catatan'] }}</div>
                                        </div>
                                    @endif
                                    <ul class="list-disc list-inside text-sm text-slate-700 space-y-1">
                                        @if(is_array($detailSubmission->alasan_belum_acuan))
                                            @foreach($detailSubmission->alasan_belum_acuan as $alasan)
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
