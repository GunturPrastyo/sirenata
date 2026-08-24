<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTestChoice extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'post_test_question_id',
        'choice',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(PostTestQuestion::class, 'post_test_question_id');
    }
}