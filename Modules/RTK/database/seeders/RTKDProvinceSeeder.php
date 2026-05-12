<?php

namespace Modules\RTK\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\MasterData\Models\Province;
use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;
use Modules\RTK\Enums\StatusDocument;

class RTKDProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua province
        $provinces = Province::all();

        if ($provinces->isEmpty()) {
            $this->command->warn('Provinces not found. Please run ProvinceSeeder first.');
            return;
        }

        DB::transaction(function () use ($provinces) {
            foreach ($provinces as $province) {
                // Ambil user admin-province yang scopeArea-nya sesuai province ini
                $user = User::role('admin-province')
                    ->whereHas('scopeArea', function ($query) use ($province) {
                        $query->where('province_code', $province->code);
                    })
                    ->first();

                if (!$user) {
                    $this->command->warn("User admin-province not found for province: {$province->name}, skipping...");
                    continue;
                }

                $data = [
                    [
                        'name'      => "RTKD Provinsi {$province->name} 2023-2024",
                        'start_date' => 2023,
                        'end_date'   => 2024,
                        'is_active'  => false,
                    ],
                    [
                        'name'      => "RTKD Provinsi {$province->name} 2024-2025",
                        'start_date' => 2024,
                        'end_date'   => 2025,
                        'is_active'  => false,
                    ],
                    [
                        'name'      => "RTKD Provinsi {$province->name} 2025-2026",
                        'start_date' => 2025,
                        'end_date'   => 2026,
                        'is_active'  => true, // hanya yang terakhir aktif
                    ],
                ];

                foreach ($data as $item) {
                    RencanaTenagaKerja::create([
                        'user_id'       => $user->id,
                        'province_code' => $province->code,
                        'regency_code'  => null,
                        'name'          => $item['name'],
                        'start_date'    => $item['start_date'],
                        'end_date'      => $item['end_date'],
                        'status_verification'        => RTKStatusVerification::PENDING->value,
                        'status_document' => StatusDocument::NA->value,
                        'type'          => TypeRtk::PROVINSI->value,
                        'is_active'     => $item['is_active'],
                        'document_path' => null,
                    ]);
                }

                $this->command->info("RTKD Province seeded for: {$province->name} ✅");
            }
        });

        $this->command->info('RTKD Province seeded successfully 🚀');
    }
}
