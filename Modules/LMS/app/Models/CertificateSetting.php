<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateSetting extends Model
{
    protected $fillable = [
        'signer_name',
        'signer_title',
        'signature_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the current active certificate setting.
     */
    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
