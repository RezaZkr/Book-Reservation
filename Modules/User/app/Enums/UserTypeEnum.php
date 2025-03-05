<?php

namespace Modules\User\Enums;

enum UserTypeEnum: string
{
    case NORMAL = 'normal';
    case VIP = 'vip';

    public function label(): string
    {
        return match ($this) {
            self::NORMAL => trans('user::enum.user_type.' . self::NORMAL->value),
            self::VIP => trans('user::enum.user_type.' . self::VIP->value),
            default => 'unknown'
        };
    }
}
