<?php

namespace Modules\Faq\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;

class FaqRegion extends Model
{
    protected $table = 'faq_regions';

    protected $fillable = [
        'faq_id',
        'province_code',
        'regency_code',
    ];

    public function faq()
    {
        return $this->belongsTo(Faq::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_code', 'code');
    }
}
