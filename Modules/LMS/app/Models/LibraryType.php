<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\LMS\Database\Factories\LibraryTypeFactory;

class LibraryType extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\Concerns\HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    // protected static function newFactory(): LibraryTypeFactory
    // {
    //     // return LibraryTypeFactory::new();
    // }
}
