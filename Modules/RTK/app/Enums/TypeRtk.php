<?php

namespace Modules\RTK\Enums;

enum TypeRtk: string
{
    case NASIONAL = 'Nasional';
    case PROVINSI = 'Provinsi';
    case KOTA = 'Kota';
    case KABUPATEN = 'Kabupaten';
    case KELURAHAN = 'Kelurahan';

    public function label(): string
    {
        return $this->value;
    }

    public static function getType($type)
    {
        return match ($type) {
            self::NASIONAL => 'Nasional',
            self::PROVINSI => 'Provinsi',
            self::KOTA => 'Kota',
            self::KABUPATEN => 'Kabupaten',
            self::KELURAHAN => 'Kelurahan',
        };
    }
}
