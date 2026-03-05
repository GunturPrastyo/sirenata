<?php

namespace Modules\Roles\Services;

use Modules\Roles\Models\Role;
use Symfony\Component\HttpFoundation\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Log;

class RoleService
{
    public function getFilteredQueryRoles(?string $search = null, string $sortBy = 'desc')
    {
        return Role::query()
            ->when($search, function ($q) use ($search) {
                $q->search($search);
            })
            ->orderBy('created_at', $sortBy);
    }


    public function paginateFilteredRoles(?string $search = null, string $sortBy = 'desc', $limit = 10)
    {
        return $this->getFilteredQueryRoles(
            search: $search,
            sortBy: $sortBy,
        )->paginate($limit)->withQueryString();
    }

    public function createRole(array $data)
    {
        $role = Role::create($data);
        ToastMagic::success("Role created successfully!");
        return $role;
    }

    public function updateRole(Role $role, array $data)
    {
        $role->update([
            'name' => $data['name'],
        ]);
        $role->syncPermissions($data['permissions'] ?? []);
        Log::info($role);
        ToastMagic::success("Role updated successfully!");
        return $role;
    }

    public function deleteRole(Role $role)
    {
        $role->delete();
        ToastMagic::success("Role deleted successfully!");
    }
}
