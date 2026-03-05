<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder modul-modul di sini agar jalan otomatis saat php artisan migrate --seed
        $this->call([
            \Modules\Roles\Database\Seeders\RolesDatabaseSeeder::class,
            \Modules\User\Database\Seeders\UserScopeSeeder::class,
            \Modules\Roles\Database\Seeders\RegionUserSeeder::class,
            \Modules\Faq\Database\Seeders\FaqDatabaseSeeder::class,
        ]);
    }
}
