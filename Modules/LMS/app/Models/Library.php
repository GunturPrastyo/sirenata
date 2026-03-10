<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\LMS\Database\Factories\LibraryFactory;

class Library extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\Concerns\HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'library_type_id',
        'title',
        'description',
        'cover_image',
        'file_path',
        'external_link',
        'created_by',
        'is_active',
    ];

    public function libraryType()
    {
        return $this->belongsTo(LibraryType::class, 'library_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // protected static function newFactory(): LibraryFactory
    // {
    //     // return LibraryFactory::new();
    // }
}
