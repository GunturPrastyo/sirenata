<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\LMS\Models\PostTestQuestion;

class PostTest extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'course_section_id',
        'course_id',
        'title',
        'description',
        'passing_score',
        'duration',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(PostTestQuestion::class);
    }
}