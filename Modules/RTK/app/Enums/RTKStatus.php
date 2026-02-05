<?php

namespace Modules\RTK\Enums;

enum RTKStatus: string
{
    case PENDING = 'Pending';                 // Baru dibuat / edit biasa
    case BERLAKU = 'Berlaku';                 // Aktif & sah
    case PENGESAHAN_ULANG = 'Pengesahan Ulang'; // Revisi dokumen saat sudah berlaku
    case TIDAK_BERLAKU = 'Tidak Berlaku';

    public function label(): string
    {
        return $this->value;
    }

    public function color(): string         
    {
        return match ($this) {
            self::PENDING => 'bg-[#fef3c7] text-[#92400e]',
            self::BERLAKU => 'bg-[#d1fae5] text-[#065f46]',
            self::PENGESAHAN_ULANG => 'bg-[#fee2e2] text-[#991b1b]',
            self::TIDAK_BERLAKU => 'bg-[#fee2e2] text-[#991b1b]',
        };
    }
}
