<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\UserUpdateRequest;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = 2;
        $search = $request->search;
        $orderBy = in_array($request->orderBy, ['asc', 'desc'])
            ? $request->orderBy
            : 'desc';

        $users = $this->userService->paginateFilteredUsers($search, $orderBy, $limit);
        return view('user::index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $permissions = Permission::select('uuid', 'name', 'module')->get();
        $roles = Role::select('uuid', 'name')->get();
        return view('user::edit', [
            'user' => $user,
            'permissions' => $permissions,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $validated = $request->validated();
            $user = $this->userService->updateUser($user, $validated);

            return redirect()->route('user.index')
                ->with('success', "User {$user->name} updated successfully!");
        } catch (\Exception $e) {
            ToastMagic::error("Failed to update user: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);
        } catch (\Exception $e) {
            ToastMagic::error("Failed to delete user: " . $e->getMessage());
            throw $e;
        }
    }
}
