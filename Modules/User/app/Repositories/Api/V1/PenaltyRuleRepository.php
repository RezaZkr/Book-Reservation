<?php

namespace Modules\User\Repositories\Api\V1;

use Modules\User\Interfaces\Api\V1\PenaltyRuleRepositoryInterface;
use Modules\User\Models\PenaltyRule;

class PenaltyRuleRepository implements PenaltyRuleRepositoryInterface
{
    public function findRuleByDelayDays(int $delayDays): ?PenaltyRule
    {
        return PenaltyRule::query()
            ->where('delay_days', '<=', $delayDays)
            ->orderBy('delay_days', 'desc')
            ->first();
        return cache()->remember(PenaltyRule::PENALTY_CACHE_KEY . $delayDays, now()->addDay(), function () use ($delayDays) {
            PenaltyRule::query()
                ->where('delay_days', '<=', $delayDays)
                ->orderBy('delay_days', 'desc')
                ->first();
        });
    }
}
