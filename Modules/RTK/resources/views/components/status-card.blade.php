@props([
    'rtk'         => null,
    'editRoute'   => null,
    'createRoute' => null,
])

@php
    use Modules\RTK\Enums\RTKStatusVerification;
    use Modules\RTK\Enums\StatusDocument;

    $config = null;

    if ($rtk) {
        $isBerlaku      = $rtk->is_berlaku;
        $isExpired      = $rtk->status_document === StatusDocument::EXPIRED;
        $isRejected     = $rtk->status_verification === RTKStatusVerification::REJECTED;
        $isPending      = $rtk->status_verification === RTKStatusVerification::PENDING;
        $isApprovedDocNA = $rtk->status_verification === RTKStatusVerification::APPROVED
            && $rtk->status_document === StatusDocument::NA;

        $config = match (true) {
            $isBerlaku       => [
                'badge'       => 'bg-emerald-50 text-emerald-800 border-emerald-200/60',
                'label'       => 'RTK Berlaku',
                'description' => 'Berlaku hingga ' . \Carbon\Carbon::parse($rtk->end_date)->format('d M Y'),
            ],
            $isApprovedDocNA => [
                'badge'       => 'bg-blue-50 text-blue-800 border-blue-200/60',
                'label'       => 'Verifikasi Disetujui',
                'description' => 'Menunggu validasi dokumen oleh Admin Pusat sebelum RTK dapat berlaku.',
            ],
            $isPending       => [
                'badge'       => 'bg-amber-50 text-amber-800 border-amber-200/60',
                'label'       => 'Menunggu Verifikasi',
                'description' => 'Dokumen sedang diverifikasi oleh Admin Pusat.',
            ],
            $isRejected      => [
                'badge'       => 'bg-rose-50 text-rose-800 border-rose-200/60',
                'label'       => 'Ditolak',
                'description' => 'Dokumen ditolak oleh ' . ($rtk->approver?->name ?? 'Admin Pusat') . '. Silakan perbaiki dokumen.',
            ],
            $isExpired       => [
                'badge'       => 'bg-orange-50 text-orange-800 border-orange-200/60',
                'label'       => 'RTK Expired',
                'description' => 'Masa berlaku dokumen RTK telah berakhir. Silakan segera menyusun RTK baru.',
            ],
            default          => [
                'badge'       => 'bg-slate-50 text-slate-700 border-slate-200/60',
                'label'       => 'Tidak Diketahui',
                'description' => null,
            ],
        };
    }
@endphp

@if($rtk && $config)
    <div class="inline-flex items-start gap-3 px-4 py-3 rounded-xl border {{ $config['badge'] }} w-full shadow-sm">
        <div class="w-full">
            <span class="font-bold text-sm block tracking-wide uppercase">{{ $config['label'] }}</span>

            @if($config['description'])
                <p class="text-xs font-medium mt-1 opacity-90 leading-relaxed">{{ $config['description'] }}</p>
            @endif

            {{-- Alasan penolakan --}}
            @if($isRejected && $rtk->rejected_reason)
                <div class="mt-2.5 text-xs bg-white/70 border border-rose-200/80 rounded-lg p-2.5 text-rose-900 leading-relaxed">
                    <span class="font-bold"><i class="fas fa-exclamation-circle mr-1"></i> Alasan:</span> {{ $rtk->rejected_reason }}
                </div>
            @endif

            {{-- Tombol Edit (Rejected) --}}
            @if($isRejected && $editRoute)
                <a href="{{ route($editRoute, $rtk->id) }}"
                    class="inline-flex items-center gap-1.5 mt-3 px-3 py-1.5 text-xs font-semibold bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213 3 21l1.787-4.5 12.075-12.013z"/>
                    </svg>
                    Edit Dokumen
                </a>
            @endif

            {{-- Tombol Buat RTK Baru (Expired) --}}
            @if($isExpired && $createRoute)
                <a href="{{ route($createRoute) }}"
                    class="inline-flex items-center gap-1 mt-3 px-3 py-1.5 text-xs font-semibold bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors shadow-sm">
                    + Buat RTK Baru
                </a>
            @endif
        </div>
    </div>
@else
    <div class="inline-flex items-center px-4 py-2.5 bg-slate-50 border border-slate-200/60 text-slate-600 rounded-xl w-full shadow-sm">
        <span class="font-bold text-sm tracking-wide uppercase"><i class="fas fa-info-circle mr-1.5 text-slate-400"></i> RTK Belum Tersedia</span>
    </div>
@endif