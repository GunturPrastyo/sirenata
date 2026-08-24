<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'content_text',
        'document',
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

    public function studentProgress(): HasMany
    {
        return $this->hasMany(StudentContentProgress::class, 'section_content_id');
    }

    // Cek apakah konten sudah selesai ditonton oleh user tertentu
    public function isCompletedByUser(string $userId): bool
    {
        return $this->studentProgress()
            ->where('user_id', $userId)
            ->exists();
    }

    // public function getVideoUrlAttribute(): ?string
    // {
    //     return $this->video
    //         ? asset('storage/' . $this->video)
    //         : null;
    // }
    public function getDocumentUrlAttribute(): ?string
    {
        return $this->document
            ? asset('storage/' . $this->document)
            : null;
    }
}
