<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\UserScope;
use App\Models\User;

class UserScopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin Provinsi (Jawa Barat - 32)
        $adminProv = User::where('email', 'adminprovinsi@gmail.com')->first();
        if ($adminProv) {
            UserScope::updateOrCreate(
                ['user_id' => $adminProv->id],
                ['province_code' => '32', 'regency_code' => null]
            );
        }

        // 2. Admin Kab Kota (Kota Bandung - 32.73)
        $adminKab = User::where('email', 'adminkabkota@gmail.com')->first();
        if ($adminKab) {
            UserScope::updateOrCreate(
                ['user_id' => $adminKab->id],
                ['province_code' => '32', 'regency_code' => '32.73']
            );
        }

        // 3. Dummy Users from RolesDatabaseSeeder
        $scopes = [
            'user.jabar@example.com' => ['province_code' => '32', 'regency_code' => null],
            'user.sumut@example.com' => ['province_code' => '12', 'regency_code' => null],
            'user.kotabandung@example.com' => ['province_code' => '32', 'regency_code' => '32.73'],
            'user.kabbandung@example.com' => ['province_code' => '32', 'regency_code' => '32.04'],
            'user.kotamedan@example.com' => ['province_code' => '12', 'regency_code' => '12.71'],
        ];

        foreach ($scopes as $email => $scope) {
            $user = User::where('email', $email)->first();
            if ($user) {
                UserScope::updateOrCreate(
                    ['user_id' => $user->id],
                    $scope
                );
                $this->command->info("Scope set for {$user->name}: Prov {$scope['province_code']}, Kab " . ($scope['regency_code'] ?? 'N/A'));
            }
        }
    }
}
