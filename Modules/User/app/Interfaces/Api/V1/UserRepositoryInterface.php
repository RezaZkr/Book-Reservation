<?php

namespace Modules\User\Interfaces\Api\V1;

interface UserRepositoryInterface
{
    public function calculatePenaltyPoint(int $loanId): int;

    public function updatePenaltyPoint(int $loanId): int;
}
