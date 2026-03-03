<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Modules\LMS\Database\Factories\CategoryFactory;

// use Modules\LMS\Database\Factories\CategoryFactory;

class Category extends Model
{
    use HasFactory, HasUuids, Sluggable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
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
                'source' => 'name',
                // 'onUpdate' => true, 
            ]
        ];
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::updating(function ($category) {
    //         $category->slug = SlugService::createSlug($category, 'slug', $category->name);
    //     });
    // }
}
