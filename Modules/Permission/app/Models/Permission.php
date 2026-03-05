<?php

namespace Modules\Permission\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Permission\Database\Factories\PermissionFactory;
use Spatie\Permission\Models\Permission  as SpatiePermission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

class Permission extends SpatiePermission
{
    use HasFactory;
    use HasUuids;
    protected $primaryKey = 'uuid';
    protected $table = 'permissions';

    #[Scope]
    protected function search(Builder $query, string $keyword): void
    {
        $query->where('name', 'like', "%{$keyword}%");
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'guard_name',
        'module',
    ];

    // protected static function newFactory(): PermissionFactory
    // {
    //     // return PermissionFactory::new();
    // }
}
