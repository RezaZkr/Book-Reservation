<?php

namespace Modules\User\Interfaces\Api\V1;

use Modules\User\Models\PenaltyRule;

interface PenaltyRuleRepositoryInterface
{
    public function findRuleByDelayDays(int $delayDays): ?PenaltyRule;
}
