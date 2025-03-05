<?php

namespace Modules\User\Repositories\Api\V1;

use Modules\Book\Enums\BookVersionConditionEnum;
use Modules\Branch\Interfaces\Api\V1\BranchRepositoryInterface;
use Modules\Reservation\Models\Loan;
use Modules\User\Interfaces\Api\V1\UserRepositoryInterface;
use Modules\User\Models\PenaltyRule;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(protected BranchRepositoryInterface $branchRepository)
    {
    }

    public function calculatePenaltyPoint(int $loanId): int
    {
        $loan = Loan::query()->findOrFail($loanId);

        if ($loan->expiration_date->greaterThanOrEqualTo(now())) {
            return 0;
        }

        $delayDays = (int)$loan->expiration_date->diffInDays(now());
        if ($delayDays <= 0) {
            return 0;
        }

        $penaltyRule = PenaltyRule::query()
            ->where('delay_days', '<=', $delayDays)
            ->orderBy('delay_days', 'desc')
            ->first();
        if (!$penaltyRule) {
            return 0;
        }

        return (int)($delayDays * $penaltyRule->penalty_rate);
    }

    public function updatePenaltyPoint(int $loanId): int
    {
        $penaltyPoint = $this->calculatePenaltyPoint($loanId);
        $loan = Loan::query()->with('user')->findOrFail($loanId);
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
}
