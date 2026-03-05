<?php

namespace Modules\Faq\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;

class FaqRegion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faq_regions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'faq_id',
        'province_code',
        'regency_code',
    ];

    /**
     * Get the FAQ associated with this region binding.
     */
    public function faq()
    {
        return $this->belongsTo(Faq::class);
    }

    /**
     * Get the province associated with this region binding.
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    /**
     * Get the regency associated with this region binding.
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_code', 'code');
    }
}
