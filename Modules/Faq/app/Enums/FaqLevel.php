<?php

namespace Modules\Faq\Enums;

enum FaqLevel: string
{
    case NASIONAL = 'Nasional';
    case PROVINSI = 'Provinsi';
    case KAB_KOTA = 'Kab/Kota';

    public function label(): string
    {
        return $this->value;
    }
}
