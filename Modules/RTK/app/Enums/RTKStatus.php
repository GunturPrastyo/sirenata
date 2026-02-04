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
            self::PENDING => 'bg-yellow-500',
            self::BERLAKU => 'bg-green-500',
            self::PENGESAHAN_ULANG => 'bg-orange-500',
            self::TIDAK_BERLAKU => 'bg-red-500',
        };
    }
}
