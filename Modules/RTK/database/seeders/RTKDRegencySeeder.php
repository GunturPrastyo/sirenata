<?php

namespace Modules\RTK\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\MasterData\Models\Regency;
use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;
use Modules\RTK\Enums\StatusDocument;

class RTKDRegencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regencies = Regency::all();

        if ($regencies->isEmpty()) {
            $this->command->warn('Regencies not found. Please run RegencySeeder first.');
            return;
        }

        DB::transaction(function () use ($regencies) {
            foreach ($regencies as $regency) {
                $user = User::role('admin-kab-kota')
                    ->whereHas('scopeArea', function ($query) use ($regency) {
                        $query->where('province_code', $regency->province_code)
                            ->where('regency_code', $regency->code);
                    })
                    ->first();

                if (!$user) {
                    $this->command->warn("User admin-kab-kota not found for regency: {$regency->name}, skipping...");
                    continue;
                }

                $data = [
                    [
                        'name'       => "RTKD Kab/Kota {$regency->name} 2023-2024",
                        'start_date' => 2023,
                        'end_date'   => 2024,
                        'is_active'  => false,
                    ],
                    [
                        'name'       => "RTKD Kab/Kota {$regency->name} 2024-2025",
                        'start_date' => 2024,
                        'end_date'   => 2025,
                        'is_active'  => false,
                    ],
                    [
                        'name'       => "RTKD Kab/Kota {$regency->name} 2025-2026",
                        'start_date' => 2025,
                        'end_date'   => 2026,
                        'is_active'  => true,
                    ],
                ];

                foreach ($data as $item) {
                    RencanaTenagaKerja::create([
                        'user_id'       => $user->id,
                        'province_code' => $regency->province_code,
                        'regency_code'  => $regency->code,
                        'name'          => $item['name'],
                        'start_date'    => $item['start_date'],
                        'end_date'      => $item['end_date'],
                        'status_verification' => RTKStatusVerification::PENDING->value,
                        'status_document' => StatusDocument::NA->value,
                        'type'          => TypeRtk::KAB_KOTA->value,
                        'is_active'     => $item['is_active'],
                        'document_path' => null,
                    ]);
                }

                $this->command->info("RTKD Kab/Kota seeded for: {$regency->name} ✅");
            }
        });

        $this->command->info('RTKD Kab/Kota seeded successfully 🚀');
    }
}
