<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use Modules\LMS\Database\Factories\SectionContentFactory;

class SectionContent extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'course_section_id',
        'name',
        'video',
        'position',
    ];

    // protected static function newFactory(): SectionContentFactory
    // {
    //     // return SectionContentFactory::new();
    // }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }
 
    public function getVideoUrlAttribute(): ?string
    {
        return $this->video
            ? asset('storage/' . $this->video)
            : null;
    }
}
