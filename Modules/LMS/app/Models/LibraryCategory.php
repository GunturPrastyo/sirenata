<?php

namespace Modules\LMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LibraryCategory extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\Concerns\HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function libraries()
    {
        return $this->hasMany(Library::class);
    }
}
