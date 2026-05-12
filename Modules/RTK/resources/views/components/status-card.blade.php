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
                'badge'       => 'bg-emerald-100 text-emerald-800',
                'label'       => 'RTK Berlaku',
                'description' => 'Berlaku hingga ' . \Carbon\Carbon::parse($rtk->end_date)->format('d M Y'),
            ],
            $isApprovedDocNA => [
                'badge'       => 'bg-blue-100 text-blue-800',
                'label'       => 'Verifikasi Disetujui',
                'description' => 'Menunggu validasi dokumen oleh Admin Pusat sebelum RTK dapat berlaku.',
            ],
            $isPending       => [
                'badge'       => 'bg-yellow-100 text-yellow-800',
                'label'       => 'Menunggu Verifikasi',
                'description' => 'Dokumen sedang diverifikasi oleh Admin Pusat.',
            ],
            $isRejected      => [
                'badge'       => 'bg-red-100 text-red-800',
                'label'       => 'Ditolak',
                'description' => 'Dokumen ditolak oleh ' . ($rtk->approver?->name ?? 'Admin Pusat') . '. Silakan perbaiki dokumen.',
            ],
            $isExpired       => [
                'badge'       => 'bg-orange-100 text-orange-800',
                'label'       => 'RTK Expired',
                'description' => 'Masa berlaku dokumen RTK telah berakhir. Silakan segera menyusun RTK baru.',
            ],
            default          => [
                'badge'       => 'bg-gray-100 text-gray-700',
                'label'       => 'Tidak Diketahui',
                'description' => null,
            ],
        };
    }
@endphp

@if($rtk && $config)
    <div class="inline-flex items-start gap-3 px-4 py-3 rounded-lg {{ $config['badge'] }}">
        <div>
            <span class="font-semibold block">{{ $config['label'] }}</span>

            @if($config['description'])
                <p class="text-xs mt-1">{{ $config['description'] }}</p>
            @endif

            {{-- Alasan penolakan --}}
            @if($isRejected && $rtk->rejected_reason)
                <div class="mt-2 text-xs bg-white/60 border border-red-200 rounded p-2">
                    <span class="font-medium">Alasan:</span> {{ $rtk->rejected_reason }}
                </div>
            @endif

            {{-- Tombol Edit (Rejected) --}}
            @if($isRejected && $editRoute)
                <a href="{{ route($editRoute, $rtk->id) }}"
                    class="inline-flex items-center gap-2 mt-3 px-3 py-1.5 text-xs font-medium bg-red-600 text-white rounded hover:bg-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213 3 21l1.787-4.5 12.075-12.013z"/>
                    </svg>
                    Edit Dokumen
                </a>
            @endif

            {{-- Tombol Buat RTK Baru (Expired) --}}
            @if($isExpired && $createRoute)
                <a href="{{ route($createRoute) }}"
                    class="inline-flex items-center gap-1 mt-3 px-3 py-1.5 text-xs font-medium bg-orange-600 text-white rounded hover:bg-orange-700">
                    + Buat RTK Baru
                </a>
            @endif
        </div>
    </div>
@else
    <div class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 rounded-lg">
        <span class="font-semibold">RTK Belum Tersedia</span>
    </div>
@endif