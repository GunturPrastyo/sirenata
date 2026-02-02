<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\User\Services\UserService;

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


    public function edit(User $user) {
        return view('user::superAdmin.user-managament-edit', [
            'user' => $user
        ]);
    }
}
