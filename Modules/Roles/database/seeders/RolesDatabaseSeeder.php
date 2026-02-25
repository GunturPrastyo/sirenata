<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Permission\Models\Permission;
use Modules\Roles\Models\Role;

class RolesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $permissions = [
            // User Management
            'user-view',
            'user-create',
            'user-edit',
            'user-delete',

            // Role Management
            'role-view',
            'role-create',
            'role-edit',
            'role-delete',

            // Permission Management
            'permission-view',
            'permission-create',
            'permission-edit',
            'permission-delete',

            'rtkn-view',
            'rtkn-list',
            'rtkn-create',
            'rtkn-edit',
            'rtkn-delete',

            'rtkd-view',
            'rtkd-list',
            'rtkd-create',
            'rtkd-edit',
            'rtkd-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // // Super Admin - Full Access
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // User - Basic Access
        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo(Permission::all());

        // Create Users dengan Role
        $superAdminUser = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $superAdminUser->assignRole('super-admin');

        // User dengan direct permission (tanpa role)
        $normalUser = \App\Models\User::create([
            'name' => 'Normal User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $normalUser->assignRole('user');

        $adminPusat = Role::create(['name' => 'admin-pusat']);
        $adminPusatUser = \App\Models\User::create([
            'name' => 'Admin Pusat',
            'email' => 'pusrenaker@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $adminPusatUser->assignRole('admin-pusat');
        
        $adminProvince = Role::create(['name' => 'admin-province']);
        $adminProvince->givePermissionTo(Permission::all());
        
        $adminProvinceUser = \App\Models\User::create([
            'name' => 'Admin Provinsi',
            'email' => 'adminprovinceJabar@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $adminProvinceUser->assignRole('admin-province');
        
        $adminKabKota = Role::create(['name' => 'admin-kab-kota']);
        $adminKabKota->givePermissionTo(Permission::all());
        
        $adminKabKotaUser = \App\Models\User::create([
            'name' => 'Admin Kabupaten/Kota',
            'email' => 'adminKabBekasi@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $adminKabKotaUser->assignRole('admin-kab-kota');
    }
}
