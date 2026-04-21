<?php

namespace Modules\RTK\Database\Seeders;

use Illuminate\Database\Seeder;

class RTKDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RTKNSeeder::class,
            RTKDProvinceSeeder::class,
            RTKDRegencySeeder::class,
        ]);
    }
}
