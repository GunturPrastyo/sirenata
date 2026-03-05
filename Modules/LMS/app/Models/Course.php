<?php

namespace Modules\LMS\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\LMS\Database\Factories\CourseFactory;

// use Modules\LMS\Database\Factories\CourseFactory;

class Course extends Model
{
    use HasFactory, HasUuids, Sluggable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'thumbnail',
        'description',
    ];

    protected static function newFactory(): CourseFactory
    {
        return CourseFactory::new();
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($course) {
            $course->slug = SlugService::createSlug($course, 'slug', $course->name);
        });
    }

    /**
     * Get the category that owns the Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student')
            ->withPivot([
                'status',
                'progress',
                'completed_at'
            ])
            ->withTimestamps();
    }

    public function mentors()
    {
        return $this->belongsToMany(User::class, 'course_mentors')
            ->using(CourseMentor::class)
            ->withPivot('is_active')
            ->withTimestamps();
    }
    
}
