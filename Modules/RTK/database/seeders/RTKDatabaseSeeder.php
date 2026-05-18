<?php

namespace Modules\RTK\Database\Seeders;

use Illuminate\Database\Seeder;

class RTKDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RTKDummySeeder::class,
            RTKNSeeder::class,
            RTKDProvinceSeeder::class,
            RTKDRegencySeeder::class,
        ]);
    }
}
