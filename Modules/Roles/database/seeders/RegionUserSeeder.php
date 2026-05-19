<?php

namespace Modules\Roles\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Modules\User\Models\UserProfile;
use Modules\User\Models\UserScope;
use Creasi\Nusa\Models\Province;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Modules\User\Enums\InstitutionType;

class RegionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = Province::all();
        $password = Hash::make('password');

        $this->command->info('Mulai generate Admin & User Regional...');

        // 31: DKI Jakarta, 32: Jawa Barat, 12: Sumatera Utara
        $focusProvinceCodes = ['31', '32', '12'];

        foreach ($provinces as $province) {
            $slugProv = Str::slug($province->name);
            
            // 1. Selalu buat Admin Provinsi untuk ke-38 provinsi
            $this->createUser(
                "admin.{$slugProv}@example.com",
                "Admin Prov {$province->name}",
                "Dinas Tenaga Kerja {$province->name}",
                InstitutionType::PROVINSI,
                ['male', 'female'][array_rand(['male', 'female'])],
                'admin-province',
                $password,
                $province->code,
                null
            );

            // 2. Hanya generate User Provinsi dan Kab/Kota untuk 3 Provinsi Fokus
            if (in_array($province->code, $focusProvinceCodes)) {
                // Buat User Provinsi
                $this->createUser(
                    "user.{$slugProv}@example.com",
                    "User Prov {$province->name}",
                    "Instansi Prov {$province->name}",
                    InstitutionType::PROVINSI,
                    ['male', 'female'][array_rand(['male', 'female'])],
                    'user',
                    $password,
                    $province->code,
                    null
                );

                // Buat SEMUA Admin & User Kab/Kota di provinsi ini
                $regencies = $province->regencies()->get();

                foreach ($regencies as $regency) {
                    $slugRegency = Str::slug($regency->name);

                    $this->createUser(
                        "admin.{$slugRegency}@example.com",
                        "Admin {$regency->name}",
                        "Dinas Tenaga Kerja {$regency->name}",
                        InstitutionType::KAB_KOTA,
                        ['male', 'female'][array_rand(['male', 'female'])],
                        'admin-kab-kota',
                        $password,
                        $province->code,
                        $regency->code
                    );

                    $this->createUser(
                        "user.{$slugRegency}@example.com",
                        "User {$regency->name}",
                        "Instansi {$regency->name}",
                        InstitutionType::KAB_KOTA,
                        ['male', 'female'][array_rand(['male', 'female'])],
                        'user',
                        $password,
                        $province->code,
                        $regency->code
                    );
                }
            }

            $this->command->info("Selesai generate region: {$province->name}");
        }

        $this->command->info('Semua data Admin & User Regional berhasil digenerate.');
    }

    private function createUser($email, $name, $instansi, $institutionType, $gender, $role, $password, $provCode, $regCode)
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => $password]
        );

        if (!$user->hasRole($role)) {
            $user->assignRole($role);
        }

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            ['full_name' => $name, 'instansi' => $instansi, 'institution_type' => $institutionType, 'gender' => $gender]
        );

        UserScope::updateOrCreate(
            ['user_id' => $user->id],
            ['province_code' => $provCode, 'regency_code' => $regCode]
        );
    }
}
