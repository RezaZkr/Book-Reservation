<?php

namespace Modules\Branch\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Branch\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            Branch::query()->firstOrCreate([
                'title' => 'Branch ' . $i,
            ]);
        }
    }
}
