<?php

namespace App\Livewire\Dashboard\SuperAdmin;

use App\Models\User;
use Creasi\Nusa\Models\Province;
use Creasi\Nusa\Models\Regency;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Modules\Permission\Models\Permission;
use Modules\Roles\Models\Role;
use Modules\User\Services\UserService;

class UserManagemanetEdit extends Component
{
    public User $user;

    public $join_date;
    public $email;
    public $full_name;
    public $phone;
    public $jabatan;
    public ?string $province = null;
    public ?string $regency  = null;

    public ?string $roleId = null;
    public array $permissionsSelected = [];


    protected UserService $userService;

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }
    

    public function mount(User $user)
    {
        $user->load(['roles', 'permissions', 'profile', 'scopeArea']);

        $this->user = $user;

        $this->email     = $user->email;
        $this->full_name = $user->profile?->full_name;
        $this->phone     = $user->profile?->phone;
        $this->jabatan   = $user->profile?->jabatan;
        $this->join_date = $user->created_at->format('Y-m-d');

        $this->roleId = $user->roles->first()?->uuid;

        $this->permissionsSelected = $user->permissions
            ->pluck('uuid')
            ->toArray();

        $this->province = $user->scopeArea?->province_code;
        $this->regency  = $user->scopeArea?->regency_code;
    }

    public function save()
    {
        try {
            $data = [
                'name'        => $this->full_name,
                'email'       => $this->email,
                'full_name'   => $this->full_name,
                'phone'       => $this->phone,
                'jabatan'     => $this->jabatan,
                'role'        => $this->roleId,
                'permissions' => $this->permissionsSelected,
                'province'    => $this->province,
                'regency'     => $this->regency,
            ];
            

            $this->userService->updateUser($this->user, $data);
            return redirect()->route('super-admin.user-management');

        } catch (\Exception $e) {
            throw $e;
        }
    }

    #[Computed()]
    public function provinces()
    {
        return Province::select('code', 'name')->get();
    }

    #[Computed()]
    public function regencies()
    {
        return Regency::where('province_code', $this->province)->select('code', 'name')->get();
    }

    public function updatedProvince($value)
    {
        $this->regency = null;
    }

    public function render()
    {
        return view('livewire.dashboard.super-admin.user-managemanet-edit', [
            'roles'       => Role::select('uuid', 'name')->get(),
            'permissions' => Permission::select('uuid', 'name')->get(),
        ]);
    }
}
