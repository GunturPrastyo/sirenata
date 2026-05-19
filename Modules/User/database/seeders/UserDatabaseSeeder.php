<?php

namespace Modules\User\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\User\Enums\InstitutionType;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user3 = User::firstOrCreate([
            'email' => 'pusat@gmail.com',
        ], [
            'name' => 'Pusat Nasional',
            'password' => bcrypt('password'),
        ]);
        if (!$user3->hasRole('user')) {
            $user3->assignRole('user');
        }
        $user3->profile()->updateOrCreate(
            ['user_id' => $user3->id],
            [
                'full_name' => 'Pusat / Nasional',
                'instansi'   => 'Kementerian Tenaga Kerja',
                'phone'     => '08123456789',
                'unit_kerja'     => 'Badan Pusat',
                'institution_type' => InstitutionType::PUSAT,
            ]
        );
        
        $user4 = User::firstOrCreate([
            'email' => 'baru@gmail.com',
        ], [
            'name' => 'Pengguna Baru',
            'password' => bcrypt('password'),
        ]);
        if (!$user4->hasRole('user')) {
            $user4->assignRole('user');
        }
        $user4->profile()->updateOrCreate(
            ['user_id' => $user4->id],
            [
                'full_name' => 'Pengguna Baru Lengkap',
                'instansi'   => null,
                'phone'     => '08123456789',
                'unit_kerja'     => null,
                'institution_type' => null,
            ]
        );
    }
}
