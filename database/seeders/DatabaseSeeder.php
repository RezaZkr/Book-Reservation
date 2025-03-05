<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\General\Database\Seeders\GeneralSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GeneralSeeder::class,
        ]);
    }
}
