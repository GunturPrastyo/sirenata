<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PostTestResult extends Model
{
    use HasUuids;

    protected $table = 'post_test_results';

    protected $fillable = [
        'user_id',
        'post_test_id',
        'score',
        'is_passed',
        'completed_at',
    ];

    protected $casts = [
        'is_passed' => 'boolean',
        'completed_at' => 'datetime',
    ];
}