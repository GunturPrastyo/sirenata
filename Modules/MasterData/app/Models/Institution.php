<?php

namespace Modules\MasterData\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Institution extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'type',
        'province_code', 
        'regency_code',
        'is_active',
    ];
}
