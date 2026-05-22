<?php

namespace Modules\Permission\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Routing\Controllers\HasMiddleware;
use Modules\Permission\Http\Requests\PermissionStoreRequest;
use Modules\Permission\Http\Requests\PermissionUpdateRequest;
use Modules\Permission\Models\Permission;
use Modules\Permission\Services\PermissionService;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class PermissionController extends Controller implements HasMiddleware
{
    public function __construct(
        private PermissionService $permissionService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('permission-view|permission-create|permission-edit|permission-delete'), only: ['index']),
            new Middleware(PermissionMiddleware::using('permission-view'), only: ['show']),
            new Middleware(PermissionMiddleware::using('permission-create'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('permission-edit'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('permission-delete'), only: ['destroy']),
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
        $permissions = $this->permissionService->paginateFilteredPermissions(
            search: $search,
            sortBy: $orderBy,
            limit: $perPage
        );
        return view('permission::index', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionStoreRequest $request)
    {
        try {
            $validate = $request->validated();
            $permission = $this->permissionService->createPermission($validate);
            return redirect()->route('super-admin.permissions.index')->with('success', 'Permission created successfully');
        } catch (\Exception $e) {
            ToastMagic::error("Failed to Create permission: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('permission::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        try {
            $validated = $request->validated();
            $permission = $this->permissionService->updatePermission($permission, $validated);
            return redirect()->route('super-admin.permissions.index')
                ->with('success', "Permission {$permission->name} updated successfully!");
        } catch (\Exception $e) {
            ToastMagic::error("Failed to Update permission: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            $this->permissionService->deletePermission($permission);
            return redirect()->route('super-admin.permissions.index')
                ->with('success', "Permission {$permission->name} deleted successfully!");
        } catch (\Exception $e) {
            ToastMagic::error("Failed to delete permission: " . $e->getMessage());
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
