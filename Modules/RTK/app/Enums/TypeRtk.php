<?php

namespace Modules\RTK\Enums;

enum TypeRtk: string
{
    case NASIONAL = 'Nasional';
    case PROVINSI = 'Provinsi';
    case KAB_KOTA   = 'Kab/Kota';

    public function label(): string
    {
        return $this->value;
    }

    public static function getType($type)
    {
        return match ($type) {
            self::NASIONAL => 'Nasional',
            self::PROVINSI => 'Provinsi',
            self::KAB_KOTA => 'Kab/Kota',
        };
    }
}
