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

            // Post Management
            'post.view',
            'post.create',
            'post.edit',
            'post.delete',

            // Project Management
            'project.view',
            'project.create',
            'project.edit',
            'project.delete',

            // Faq Management
            'faq.view',
            'faq.create',
            'faq.edit',
            'faq.delete',

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
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Super Admin - Full Access
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin Pusat
        $adminPusat = Role::firstOrCreate(['name' => 'admin-pusat']);
        $adminPusat->givePermissionTo(Permission::all());

        // Admin Province
        $adminProvince = Role::firstOrCreate(['name' => 'admin-province']);
        $adminProvince->givePermissionTo(Permission::all());

        // Admin Kab Kota
        $adminKabKota = Role::firstOrCreate(['name' => 'admin-kab-kota']);
        $adminKabKota->givePermissionTo(Permission::all());

        // User - Basic Access
        $user = Role::firstOrCreate(['name' => 'user']);
        $user->givePermissionTo(Permission::all());

        // Create Users dengan Role
        $superAdminUser = \App\Models\User::firstOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
        ]);
        $superAdminUser->assignRole('super-admin');

        // Admin Pusat User
        $adminPusatUser = \App\Models\User::firstOrCreate([
            'email' => 'adminpusat@gmail.com',
        ], [
            'name' => 'Admin Pusat',
            'password' => bcrypt('password'),
        ]);
        $adminPusatUser->assignRole('admin-pusat');

        // Admin Province User
        $adminProvinceUser = \App\Models\User::firstOrCreate([
            'email' => 'adminprovinsi@gmail.com',
        ], [
            'name' => 'Admin Provinsi',
            'password' => bcrypt('password'),
        ]);
        $adminProvinceUser->assignRole('admin-province');

        // Admin Kab Kota User
        $adminKabKotaUser = \App\Models\User::firstOrCreate([
            'email' => 'adminkabkota@gmail.com',
        ], [
            'name' => 'Admin Kab Kota',
            'password' => bcrypt('password'),
        ]);
        $adminKabKotaUser->assignRole('admin-kab-kota');

        // User dengan direct permission (tanpa role)
        $normalUser = \App\Models\User::firstOrCreate([
            'email' => 'user@gmail.com',
        ], [
            'name' => 'Normal User',
            'password' => bcrypt('password'),
        ]);
        $normalUser->assignRole('user');
        $normalUser->givePermissionTo('post.create');
        // This user will purposely NOT have an instansi to test the modal
        \Modules\User\Models\UserProfile::firstOrCreate([
            'user_id' => $normalUser->id
        ], [
            'full_name' => 'Normal User (No Instansi)',
            'gender' => 'male',
        ]);

        // Create Dummy Users for testing — 1 per location level
        $dummyUsers = [
            ['email' => 'user.pusat@example.com', 'name' => 'User Pusat', 'instansi' => 'Kementerian Pusat', 'gender' => 'male'],
            ['email' => 'user.jabar@example.com', 'name' => 'User Prov Jawa Barat', 'instansi' => 'Dinas Tenaga Kerja Jabar', 'gender' => 'female'],
            ['email' => 'user.sumut@example.com', 'name' => 'User Prov Sumatera Utara', 'instansi' => 'Dinas Tenaga Kerja Sumut', 'gender' => 'male'],
            ['email' => 'user.kotabandung@example.com', 'name' => 'User Kota Bandung', 'instansi' => 'BLK Kota Bandung', 'gender' => 'female'],
            ['email' => 'user.kabbandung@example.com', 'name' => 'User Kab Bandung', 'instansi' => 'BLK Kab Bandung', 'gender' => 'male'],
            ['email' => 'user.kotamedan@example.com', 'name' => 'User Kota Medan', 'instansi' => 'BLK Kota Medan', 'gender' => 'female'],
        ];

        foreach ($dummyUsers as $data) {
            $dummyUser = \App\Models\User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => bcrypt('password')]
            );
            $dummyUser->assignRole('user');

            \Modules\User\Models\UserProfile::firstOrCreate(
                ['user_id' => $dummyUser->id],
                ['full_name' => $data['name'], 'instansi' => $data['instansi'], 'gender' => $data['gender']]
            );
        }
    }
}
