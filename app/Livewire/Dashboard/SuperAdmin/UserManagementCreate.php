<?php

namespace App\Livewire\Dashboard\SuperAdmin;


use Livewire\Attributes\Computed;
use Livewire\Component;
use Modules\Permission\Models\Permission;
use Modules\Roles\Models\Role;
use Modules\User\Services\UserService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Validation\Rules\Password;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;

class UserManagementCreate extends Component
{
    public $name;
    public $full_name;
    public $email;
    public $phone;
    public $instansi;
    public $join_date;
    public $province;
    public $regency;
    public array $roleIds = [];
    public ?string $password = null;
    public ?string $password_confirmation = null;

    protected UserService $userService;

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
            'phone' => 'required|string|max:20',
            'roleIds' => 'required|array|min:1|max:2',
            'roleIds.*' => 'exists:roles,uuid',
            'instansi' => 'required|string|max:255',
            'province' => 'nullable|string|size:2',
            'regency'  => 'nullable|string|max:5',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'roleIds' => 'role',
        ];
    }

    public function store()
    {
        try {
            $this->validate();
            $data = [
                'name'        => $this->name,
                'email'       => $this->email,
                'full_name'   => $this->full_name,
                'phone'       => $this->phone,
                'instansi'     => $this->instansi,
                'roles'        => $this->roleIds, 
                'province'    => $this->province,
                'regency'     => $this->regency,
                'password'    => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ];

            $this->userService->createUser($data);

            return redirect()->route('super-admin.user-management.index');
        } catch (\Exception $e) {
            ToastMagic::error($e->getMessage());
            throw $e;
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

    public function updatedProvince($value)
    {
        $this->regency = null;
    }

    public function render()
    {
        return view('livewire.dashboard.super-admin.user-management-create', [
            'roles'       => Role::select('uuid', 'name')->orderBy('name', 'asc')->get(),
        ]);
    }
}
