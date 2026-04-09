<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\LMS\Database\Factories\CourseBenefitsFactory;

class CourseBenefits extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'course_id',
        'name',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // protected static function newFactory(): CourseBenefitsFactory
    // {
    //     // return CourseBenefitsFactory::new();
    // }
}
