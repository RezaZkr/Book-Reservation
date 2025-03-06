<?php

namespace Modules\User\Repositories\Api\V1;

use Modules\Book\Enums\BookVersionConditionEnum;
use Modules\Branch\Interfaces\Api\V1\BranchRepositoryInterface;
use Modules\Reservation\Interfaces\Api\V1\LoanRepositoryInterface;
use Modules\User\Interfaces\Api\V1\PenaltyRuleRepositoryInterface;
use Modules\User\Interfaces\Api\V1\UserRepositoryInterface;
use Modules\User\Models\PenaltyRule;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected BranchRepositoryInterface      $branchRepository,
        protected PenaltyRuleRepositoryInterface $penaltyRuleRepository,
        protected LoanRepositoryInterface        $loanRepository,
    )
    {
    }

    public function calculatePenaltyPoint(int $loanId): int
    {
        if (!$loan = $this->loanRepository->findById($loanId)) {
            return 0;
        }

        if ($loan->expiration_date->greaterThanOrEqualTo(now())) {
            return 0;
        }

        $delayDays = (int)$loan->expiration_date->diffInDays(now());
        if ($delayDays <= 0) {
            return 0;
        }

        if (!$penaltyRule = $this->penaltyRuleRepository->findRuleByDelayDays($delayDays)) {
            return 0;
        }

        return (int)($delayDays * $penaltyRule->penalty_rate);
    }

    public function updatePenaltyPointOnBookReturn(int $loanId): int
    {
        $penaltyPoint = 0;

        if (!$loan = $this->loanRepository->findById($loanId)) {
            return $penaltyPoint;
        }

        $loan->loadMissing('user');

        if ($loan->receive_status === BookVersionConditionEnum::DAMAGED) {
            $penaltyPoint += PenaltyRule::NEGATIVE_DAMAGE_POINT;
            $loan->user->restricted = true;
        }

        // increase penalty points if user repeat violations
        if ($loan->user->penalty_points > 0) {
            $penaltyPoint += PenaltyRule::NEGATIVE_REPEAT_VIOLATION_POINT;
        }

        $loan->user->penalty_points += $penaltyPoint;
        $loan->user->save();

        return $penaltyPoint;
    }

    public function updateDailyPenaltyPoint(int $loanId): int
    {
        if (!$penaltyRule = $this->penaltyRuleRepository->findRuleByDelayDays(1)) {
            return 0;
        }

        if (!$loan = $this->loanRepository->findById($loanId)) {
            return 0;
        }

        $loan->loadMissing('user');

        $loan->user->penalty_points += $penaltyRule->penalty_rate;
        $loan->user->save();

        return $penaltyRule->penalty_rate;
    }
}
