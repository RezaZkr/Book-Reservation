<?php

namespace Modules\Reservation\Enums;

enum LoanStatusEnum: string
{
    case ACTIVE = 'active';
    case RETURNED = 'returned';
    case LOST = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => trans('reservation::enum.loan_status.' . self::ACTIVE->value),
            self::RETURNED => trans('reservation::enum.loan_status.' . self::RETURNED->value),
            self::LOST => trans('reservation::enum.loan_status.' . self::LOST->value),
            default => 'unknown'
        };
    }
}
