<?php

namespace Modules\Reservation\Enums;

enum LoanStatusEnum: string
{
    case ACTIVE = 'active';
    case RETURNED_ON_TIME = 'returned_on_time';
    case LATE_RETURN = 'late_return';
    case LOST = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => trans('reservation::enum.loan_status.' . self::ACTIVE->value),
            self::RETURNED_ON_TIME => trans('reservation::enum.loan_status.' . self::RETURNED_ON_TIME->value),
            self::LATE_RETURN => trans('reservation::enum.loan_status.' . self::LATE_RETURN->value),
            self::LOST => trans('reservation::enum.loan_status.' . self::LOST->value),
            default => 'unknown'
        };
    }
}
