<?php

namespace Modules\Permission\Services;

use Devrabiul\ToastMagic\Facades\ToastMagic;
use Modules\Permission\Models\Permission;

class PermissionService
{
    public function getFilteredQueryPermissions(?string $search = null, string $sortBy = 'desc')
    {
        return Permission::query()
            ->when($search, function ($q) use ($search) {
                $q->search($search);
            })
            ->orderBy('created_at', $sortBy);
    }


    public function paginateFilteredPermissions(?string $search = null, string $sortBy = 'desc', $limit = 10)
    {
        return $this->getFilteredQueryPermissions(
            search: $search,
            sortBy: $sortBy
        )->paginate($limit)->withQueryString();
    }

    public function createPermission(array $data)
    {
        $permission = Permission::create($data);
        ToastMagic::success("Permission created successfully!");
        return $permission;
    }

    public function updatePermission(Permission $permission, array $data)
    {
        $permission->update($data);
        ToastMagic::success("Permission updated successfully!");
        return $permission;
    }

    public function deletePermission(Permission $permission)
    {
        $permission->delete();
        ToastMagic::success("Permission deleted successfully!");
    }
}
