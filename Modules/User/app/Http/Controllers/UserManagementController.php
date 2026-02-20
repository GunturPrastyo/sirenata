<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\User\Services\UserService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class UserManagementController extends Controller implements HasMiddleware
{

    public function __construct(
        private UserService $userService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('user-view|user-create|user-edit|user-delete'), only: ['index']),
            // new Middleware(PermissionMiddleware::using('user-view'), only: ['show']),
            // new Middleware(PermissionMiddleware::using('user-create'), only: ['create', 'store']),
            // new Middleware(PermissionMiddleware::using('user-edit'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('user-delete'), only: ['destroy']),
        ];
    }   

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        return view('user::superAdmin.user-managament');
    }


    public function create(Request $request) {
        return view('user::superAdmin.user-managament-create');
    }
    
    public function show(User $user) {
        $user->load(['profile', 'scopeArea']);
        return view('user::superAdmin.user-managament-show', [
            'user' => $user
        ]);
    }

    
    public function edit(User $user) {
        return view('user::superAdmin.user-managament-edit', [
            'user' => $user
        ]);
    }

    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);
            ToastMagic::success("User berhasil dihapus!");
            return redirect()->route('super-admin.user-management.index');
        } catch (\Exception $e) {
            ToastMagic::error("Gagal menghapus user: " . $e->getMessage());
            throw $e;
        }
    }
}
