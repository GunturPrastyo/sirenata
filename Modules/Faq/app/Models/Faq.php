<?php

namespace Modules\Faq\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'level',
        'created_by',
    ];


    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
