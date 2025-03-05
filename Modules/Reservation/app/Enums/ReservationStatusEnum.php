<?php

namespace Modules\Reservation\Enums;

enum ReservationStatusEnum: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => trans('reservation::enum.reservation_status.' . self::PENDING->value),
            self::ACCEPTED => trans('reservation::enum.reservation_status.' . self::ACCEPTED->value),
            self::REJECTED => trans('reservation::enum.reservation_status.' . self::REJECTED->value),
            default => 'unknown'
        };
    }
}
