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
        // $this->call([]);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'acehkabkota@gmail.com',
        ]);
        $user->assignRole('user');
        $user->profile()->create([
            'full_name' => 'Test User',
            'instansi'   => 'Test Instansi',
            'phone'     => '08123456789',
            'unit_kerja'     => 'test Instansi',
            'institution_type' => InstitutionType::KAB_KOTA,
        ]);

        $user->scopeArea()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'province_code' => '11',
                'regency_code'  => '11.01',
            ]
        );


        $user2 = User::factory()->create([
            'name' => 'Aceh Provinsi',
            'email' => 'acehprov@gmail.com',
        ]);
        $user2->assignRole('user');
        $user2->profile()->create([
            'full_name' => 'Aceh Provinsi',
            'instansi'   => 'Aceh Provinsi',
            'phone'     => '08123456789',
            'unit_kerja'     => 'Aceh Provinsi',
            'institution_type' => InstitutionType::PROVINSI,
        ]);

        $user2->scopeArea()->updateOrCreate(
            ['user_id' => $user2->id],
            [
                'province_code' => '11',
            ]
        );
    }
}
