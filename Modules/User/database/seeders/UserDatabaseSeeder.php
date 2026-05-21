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
        $pusatUsers = [
            [
                'email' => 'pusat@gmail.com',
                'name' => 'Pusat Nasional 1',
                'full_name' => 'Pusat / Nasional 1',
                'instansi' => 'Kementerian Ketenagakerjaan',
                'phone' => '081234567891',
                'unit_kerja' => 'Direktorat Jenderal Pembinaan Pelatihan Vokasi',
            ],
            [
                'email' => 'pusat2@gmail.com',
                'name' => 'Pusat Nasional 2',
                'full_name' => 'Pusat / Nasional 2',
                'instansi' => 'Kementerian Ketenagakerjaan',
                'phone' => '081234567892',
                'unit_kerja' => 'Direktorat Jenderal Pembinaan Penempatan Tenaga Kerja',
            ],
            [
                'email' => 'pusat3@gmail.com',
                'name' => 'Pusat Nasional 3',
                'full_name' => 'Pusat / Nasional 3',
                'instansi' => 'Kementerian Ketenagakerjaan',
                'phone' => '081234567893',
                'unit_kerja' => 'Badan Perencanaan dan Pengembangan Ketenagakerjaan',
            ]
        ];

        foreach ($pusatUsers as $userData) {
            $user = User::firstOrCreate([
                'email' => $userData['email'],
            ], [
                'name' => $userData['name'],
                'password' => bcrypt('password'),
            ]);

            if (!$user->hasRole('user')) {
                $user->assignRole('user');
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $userData['full_name'],
                    'instansi'   => $userData['instansi'],
                    'phone'     => $userData['phone'],
                    'unit_kerja'     => $userData['unit_kerja'],
                    'institution_type' => InstitutionType::PUSAT,
                ]
            );
        }
    }
}
