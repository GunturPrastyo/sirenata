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
    public $jabatan;
    public $join_date;
    public $province;
    public $regency;
    public ?string $roleId = null;
    public array $permissionsSelected = [];
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
            'roleId' => 'required|exists:roles,uuid',
            'jabatan' => 'required|string|max:255',
            'province' => 'nullable|string|size:2',
            'regency'  => 'nullable|string|max:5',
            'permissionsSelected' => 'required|array',
            'permissionsSelected.*' => 'required|exists:permissions,uuid',
        ];
    }

    protected function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'email.unique' => 'The email is already registered in our system.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Password dan konfirmasinya tidak cocok.',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'roleId' => 'role',
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
                'jabatan'     => $this->jabatan,
                'role'        => $this->roleId, 
                'permissions' => $this->permissionsSelected,
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
            'permissions' => Permission::select('uuid', 'name')->orderBy('name', 'asc')->get(),
        ]);
    }
}
