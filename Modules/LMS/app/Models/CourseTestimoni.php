<?php

namespace Modules\LMS\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use Modules\LMS\Database\Factories\CoursesTestimonisFactory;

class CourseTestimoni extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'course_id',
        'user_id',
        'name',
        'description',
    ];

    protected $table = 'courses_testimonis';

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // protected static function newFactory(): CoursesTestimonisFactory
    // {
    //     // return CoursesTestimonisFactory::new();
    // }
}
