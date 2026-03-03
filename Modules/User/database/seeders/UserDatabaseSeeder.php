<?php

namespace Modules\User\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\User\Enums\IntantionLevel;
use Modules\User\Enums\IntantionType;

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
            'email' => 'user@gmail.com',
        ]);
        $user->assignRole('user');
        $user->profile()->create([
            'full_name' => 'Test User',
            'instansi'   => 'Test Instansi',
            'phone'     => '08123456789',
            'unit_kerja'     => 'test Instansi',
            'institution_type' => IntantionType::TYPE_PUSAT,
            'institution_level' => IntantionLevel::PROVINSI,
        ]);

        $user->scopeArea()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'province_code' => '11',
                'regency_code'  => '11.01',
            ]
        );
    }
}
