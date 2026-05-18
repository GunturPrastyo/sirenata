<?php

namespace Modules\RTK\Enums;

enum StatusDocument: string
{
    case VALID   = 'valid';
    case EXPIRED = 'expired';
    case NA      = 'na'; // N/A — belum ada dokumen / belum diverifikasi

    public function label(): string
    {
        return match ($this) {
            self::VALID   => 'Berlaku',
            self::EXPIRED => 'Kadaluarsa',
            self::NA      => 'N/A',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::VALID   => 'bg-green-100 text-green-800',
            self::EXPIRED => 'bg-red-100 text-red-800',
            self::NA      => 'bg-gray-100 text-gray-800',
        };
    }
}
