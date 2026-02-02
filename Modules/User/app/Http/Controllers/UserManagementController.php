<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\User\Services\UserService;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class UserManagementController extends Controller
{

    public function __construct(
        private UserService $userService
    ) {}

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
