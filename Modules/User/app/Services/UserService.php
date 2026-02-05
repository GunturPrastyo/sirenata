<?php

namespace Modules\User\Services;

use App\Models\User;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Permission\Models\Permission;
use Modules\Roles\Models\Role;
use Spatie\Activitylog\Models\Activity;

class UserService
{
    public function getFilteredQueryUsers(?string $search = null, string $sortBy = 'desc')
    {
        return User::query()->with(['roles', 'permissions','scopeArea.province','scopeArea.regency'])
            ->when($search, function ($q) use ($search) {
                $q->search($search);
            })
            ->orderBy('created_at', $sortBy);
    }

    public function paginateFilteredUsers(?string $search = null, string $sortBy = 'desc', $limit = 10)
    {
        return $this->getFilteredQueryUsers($search, $sortBy)->paginate($limit)->withQueryString();
    }

    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $oldRoles = $user->getRoleNames()->toArray();
            $oldPermissions = $user->getPermissionNames()->toArray();

            $user->update([
                'name'  => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
            ]);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $data['full_name'],
                    'jabatan'   => $data['jabatan'],
                    'phone'     => $data['phone'],
                ]
            );

            $user->scopeArea()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'province_code' => $data['province'] ?: null,
                    'regency_code'  => $data['regency'] ?: null,
                ]
            );

            if (!empty($data['password'])) {
                $user->update(['password' => bcrypt($data['password'])]);
            }

            $role = Role::where('uuid', $data['role'])->firstOrFail();
            $newRoles = [$role->name];

            if (array_diff($oldRoles, $newRoles) || array_diff($newRoles, $oldRoles)) {
                $user->syncRoles($newRoles);
                activity('user-role')
                    ->causedBy(Auth::user())
                    ->performedOn($user)
                    ->withProperties([
                        'old' => $oldRoles,
                        'attributes' => $newRoles,
                    ])
                    ->log('User role updated');
            }
            // {"attributes":
            $newPermissions = Permission::whereIn('uuid', $data['permissions'] ?? [])
                ->pluck('name')
                ->toArray();

            sort($oldPermissions);
            sort($newPermissions);

            if ($oldPermissions !== $newPermissions) {
                $user->syncPermissions($newPermissions);
                activity('user-permission')
                    ->causedBy(Auth::user())
                    ->performedOn($user)
                    ->withProperties([
                        'old' => $oldPermissions,
                        'attributes' => $newPermissions,
                    ])
                    ->log('User permissions updated');
            }

            ToastMagic::success("User {$user->name} updated successfully!");

            return $user;
        });
    }

    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->profile()->create([
                'full_name' => $data['full_name'],
                'jabatan'   => $data['jabatan'],
                'phone'     => $data['phone'],
            ]);

            $user->scopeArea()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'province_code' => $data['province'] ?: null,
                    'regency_code'  => $data['regency'] ?: null,
                ]
            );

            $role = Role::where('uuid', $data['role'])->firstOrFail();
            $user->assignRole($role);

            $permissions = Permission::whereIn('uuid', $data['permissions'] ?? [])
                ->pluck('name')
                ->toArray();

            $user->syncPermissions($permissions);

            ToastMagic::success("User {$user->name} created successfully!");

            return $user;
        });
    }


    public function deleteUser(User $user): void
    {
        DB::transaction(function () use ($user) {
            // Lepas semua role & permission (best practice Spatie)
            $user->syncRoles([]);
            $user->syncPermissions([]);

            // Hapus user
            $user->delete();
        });
    }
}
