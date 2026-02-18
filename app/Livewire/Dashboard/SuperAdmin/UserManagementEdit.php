<?php

namespace App\Livewire\Dashboard\SuperAdmin;

use App\Models\User;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Modules\Permission\Models\Permission;
use Modules\Roles\Models\Role;
use Modules\User\Services\UserService;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class UserManagementEdit extends Component
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


    protected function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'phone' => 'required|string|max:20',
            'roleId' => 'required|exists:roles,uuid',
            'jabatan' => 'required|string|max:255',
            'province' => 'nullable|string|size:2',
            'regency'  => 'nullable|string|max:5',
            'permissionsSelected' => 'required|array',
            'permissionsSelected.*' => 'required|exists:permissions,uuid',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'roleId' => 'role',
        ];
    }

    public function save()
    {
        try {
            $this->validate();
            $data = [
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
            return redirect()->route('super-admin.user-management.index');

        } catch (\Exception $e) {
            ToastMagic::error($e->getMessage());
            throw $e;
        }
    }

    public function updatedProvince($value)             
    {
        if (empty($value)) {
            $this->regency = null;
        }
    }

    #[Computed()]
    public function provinces()
    {
        return Province::select('code', 'name')->lazy();
    }

    #[Computed()]
    public function regencies()
    {
        return Regency::where('province_code', $this->province)->select('code', 'name')->lazy();
    }

    public function render()
    {
        return view('livewire.dashboard.super-admin.user-management-edit', [
            'roles'       => Role::select('uuid', 'name')->get(),
            'permissions' => Permission::select('uuid', 'name')->get(),
        ]);
    }
}
