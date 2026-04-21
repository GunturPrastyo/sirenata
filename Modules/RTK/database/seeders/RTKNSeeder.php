<?php
namespace Modules\RTK\Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;
use App\Models\User;
use Carbon\Carbon;

class RTKNSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::role('admin-pusat')->first();

        if (!$user) {
            $this->command->warn('User with role admin-pusat not found. Please run RoleSeeder & UserSeeder first.');
            return;
        }

       $data = [
            [
                'name'       => 'RTKN 2023-2024',
                'start_date' => 2023,
                'end_date'   => 2024,
                'is_active'  => false,
            ],
            [
                'name'       => 'RTKN 2024-2025',
                'start_date' => 2024,
                'end_date'   => 2025,
                'is_active'  => false,
            ],
            [
                'name'       => 'RTKN 2025-2026',
                'start_date' => 2025,
                'end_date'   => 2026,
                'is_active'  => true,
            ],
        ];

        DB::transaction(function () use ($data, $user) {
            foreach ($data as $item) {
                RencanaTenagaKerja::create([
                    'user_id'       => $user->id,
                    'name'          => $item['name'],
                    'start_date'    => $item['start_date'],
                    'end_date'      => $item['end_date'],
                    'status'        => RTKStatus::PENDING->value,
                    'is_active'     => $item['is_active'],
                    'type'          => TypeRtk::NASIONAL->value,
                    'document_path' => null,
                ]);
            }
        });

        $this->command->info('RTKN seeded successfully 🚀');
    }
}