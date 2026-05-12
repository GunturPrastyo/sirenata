<?php

namespace Modules\LMS\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use Modules\LMS\Database\Factories\StudentContentProgressFactory;

class StudentContentProgress extends Model
{
    use HasFactory;
    protected $table = 'student_content_progress';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'section_content_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // protected static function newFactory(): StudentContentProgressFactory
    // {
    //     // return StudentContentProgressFactory::new();
    // }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function content(): BelongsTo
    {
        return $this->belongsTo(SectionContent::class, 'section_content_id');
    }

}
