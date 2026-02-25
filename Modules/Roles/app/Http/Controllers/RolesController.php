<?php

namespace Modules\Roles\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Modules\Roles\Http\Requests\RoleStoreRequest;
use Modules\Roles\Http\Requests\RoleUpdateRequest;
use Modules\Roles\Models\Role;
use Modules\Roles\Services\RoleService;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Modules\Permission\Models\Permission;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RolesController extends Controller implements HasMiddleware
{
    public function __construct(
        private RoleService $roleService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('role-view|role-create|role-edit|role-delete'), only: ['index']),
            new Middleware(PermissionMiddleware::using('role-view'), only: ['show']),
            new Middleware(PermissionMiddleware::using('role-create'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('role-edit'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('role-delete'), only: ['destroy']),
        ];
    }   

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->search;
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';
        $perPage = in_array($request->per_page, [10, 20, 50, 100])
            ? $request->per_page
            : 10;
            
        $roles =  $this->roleService->paginateFilteredRoles(
            search: $search,
            sortBy: $orderBy,
            limit: $perPage
        );
        return view('roles::index', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $role = $this->roleService->createRole($validated);
            return redirect()->route('super-admin.roles.index')->with('success', 'Role created successfully');
        } catch (\Exception $e) {
            ToastMagic::error("Failed to create role: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('roles::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all()
        ->groupBy(function ($permission) {
            return explode('-', $permission->name)[0];
        });
        return view('roles::edit', [
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $role = $this->roleService->updateRole($role, $validated);
            return redirect()->route('super-admin.roles.index')
                ->with('success', "Role {$role->name} updated successfully!");
        } catch (\Exception $e) {
            Log::info($e);
            ToastMagic::error("Failed to update role: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $this->roleService->deleteRole($role);
            return redirect()->route('super-admin.roles.index')
                ->with('success', "Role {$role->name} deleted successfully!");
        } catch (\Exception $e) {
            ToastMagic::error("Failed to delete role: " . $e->getMessage());
            throw $e;
        }
    }
}
