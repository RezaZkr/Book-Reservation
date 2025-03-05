<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\PenaltyRule;

class PenaltyRuleSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            PenaltyRule::query()->firstOrCreate([
                'delay_days' => $i,
            ], [
                'penalty_rate' => $i + 1,
            ]);
        }
    }
}
