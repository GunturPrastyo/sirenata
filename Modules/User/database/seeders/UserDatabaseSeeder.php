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
        $user3 = User::factory()->create([
            'name' => 'Pusat Nasional',
            'email' => 'pusat@gmail.com',
        ]);
        $user3->assignRole('user');
        $user3->profile()->create([
            'full_name' => 'Pusat / Nasional',
            'instansi'   => 'Kementerian Tenaga Kerja',
            'phone'     => '08123456789',
            'unit_kerja'     => 'Badan Pusat',
            'institution_type' => InstitutionType::PUSAT,
        ]);
        // No ScopeArea created for $user3 so they remain at the highest level (Pusat/Nasional)
        
        $user4 = User::factory()->create([
            'name' => 'Pengguna Baru',
            'email' => 'baru@gmail.com',
        ]);
        $user4->assignRole('user');
        $user4->profile()->create([
            'full_name' => 'Pengguna Baru Lengkap',
            'instansi'   => null, // Deliberately null to trigger the Dashboard modal
            'phone'     => '08123456789',
            'unit_kerja'     => null,
            'institution_type' => null,
        ]);
    }
}
