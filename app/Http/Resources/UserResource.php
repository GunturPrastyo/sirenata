<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            /**
             * @example "user@bestapi.dev"
             */
            'email' => $this->email,
            'roles' => $this->whenLoaded('roles', fn() => $this->roles->map(fn($role) => [
                'id'          => $role->uuid,
                'name'        => $role->name,
                'permissions' => $role->relationLoaded('permissions')
                    ? $role->permissions->map(fn($permission) => [
                        'id'   => $permission->uuid,
                        'name' => $permission->name,
                    ])
                    : null,
            ])),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
