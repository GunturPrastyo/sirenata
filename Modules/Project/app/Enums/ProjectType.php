<?php

namespace Modules\Project\Enums;

enum ProjectType: string
{
    case NASIONAL = 'Nasional';
    case PROVINSI = 'Provinsi';
    case KAB_KOTA = 'Kab/Kota';

    public function label(): string
    {
        return $this->value;
    }
}
