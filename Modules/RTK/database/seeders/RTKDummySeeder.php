<?php

namespace Modules\RTK\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\RTK\Models\RencanaTenagaKerja;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\TypeRtk;
use Carbon\Carbon;
use Creasi\Nusa\Models\Province;
use App\Models\User;
use Illuminate\Support\Str;

class RTKDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kode Provinsi di Pulau Jawa
        $javaProvinceCodes = ['31', '32', '33', '34', '35', '36'];
        $provinces = Province::whereIn('code', $javaProvinceCodes)->get();

        if ($provinces->isEmpty()) {
            $this->command->warn("Data provinsi belum tersedia (mungkin Nusa seeder belum jalan).");
            return;
        }

        foreach ($provinces as $province) {
            // Mencari user Admin dari provinsi tersebut yang digenerate oleh RegionUserSeeder
            $slugProv = Str::slug($province->name);
            $userProv = User::where('email', "admin.{$slugProv}@example.com")->first() 
                        ?? User::where('email', 'adminprovinsi@gmail.com')->first()
                        ?? User::first();

            // Membuat 1 sampai 3 data RTK level Provinsi
            $jmlProvRTK = rand(1, 3);
            for ($i = 0; $i < $jmlProvRTK; $i++) {
                $startYear = rand(2015, 2030);
                $endYear = rand($startYear + 1, 2035);
                $statusCases = RTKStatus::cases();

                RencanaTenagaKerja::create([
                    'user_id' => $userProv->id,
                    'province_code' => $province->code,
                    'name' => "Rencana Tenaga Kerja Provinsi {$province->name} $startYear-$endYear",
                    'start_date' => $startYear,
                    'end_date' => $endYear,
                    'status' => $statusCases[array_rand($statusCases)],
                    'is_active' => true,
                    'type' => TypeRtk::PROVINSI,
                ]);
            }

            // Memilih beberapa kabupaten secara acak dari provinsi ini
            $regencies = $province->regencies()->inRandomOrder()->take(rand(2, 4))->get();
            
            foreach ($regencies as $regency) {
                $slugRegency = Str::slug($regency->name);
                $userRegency = User::where('email', "admin.{$slugRegency}@example.com")->first() 
                               ?? User::where('email', 'adminkabkota@gmail.com')->first()
                               ?? User::first();

                // Membuat 1 sampai 3 data RTK level Kab/Kota
                $jmlKabRTK = rand(1, 3);
                for ($i = 0; $i < $jmlKabRTK; $i++) {
                    $startYear = rand(2015, 2030);
                    $endYear = rand($startYear + 1, 2035);
                    $statusCases = RTKStatus::cases();

                    RencanaTenagaKerja::create([
                        'user_id' => $userRegency->id,
                        'province_code' => $province->code,
                        'regency_code' => $regency->code,
                        'name' => "Rencana Tenaga Kerja {$regency->name} $startYear-$endYear",
                        'start_date' => $startYear,
                        'end_date' => $endYear,
                        'status' => $statusCases[array_rand($statusCases)],
                        'is_active' => true,
                        'type' => TypeRtk::KAB_KOTA,
                    ]);
                }
            }
        }
        
        $this->command->info("Berhasil menambahkan dummy data RTK untuk pulau Jawa!");
    }
}
