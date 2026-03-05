<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

// use Modules\LMS\Database\Factories\CourseMentorFactory;

class CourseMentor extends Pivot
{
    use HasFactory;

    protected $table = 'course_mentors';

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): CourseMentorFactory
    // {
    //     // return CourseMentorFactory::new();
    // }
}
