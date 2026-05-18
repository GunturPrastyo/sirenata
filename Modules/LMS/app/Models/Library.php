<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Library extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\Concerns\HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'library_category_id',
        'title',
        'description',
        'cover_image',
        'file_path',
        'video_path',
        'external_link',
        'created_by',
    ];

    public function libraryCategory()
    {
        return $this->belongsTo(LibraryCategory::class, 'library_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
