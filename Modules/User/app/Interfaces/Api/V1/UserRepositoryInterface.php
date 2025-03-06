<?php

namespace Modules\User\Interfaces\Api\V1;

interface UserRepositoryInterface
{
    public function calculatePenaltyPoint(int $loanId): int;

    public function updatePenaltyPointOnBookReturn(int $loanId): int;
    public function updateDailyPenaltyPoint(int $loanId): int;
}
