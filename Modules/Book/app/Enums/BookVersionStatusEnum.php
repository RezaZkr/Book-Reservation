<?php

namespace Modules\Book\Enums;

enum BookVersionStatusEnum: string
{
    case IN_REPAIR = 'in_repair';
    case AVAILABLE = 'available';
    case RESERVED = 'reserved';
    case UN_RESERVABLE = 'un_reservable';

    public function label(): string
    {
        return match ($this) {
            self::IN_REPAIR => trans('book::enum.book_version_status.' . self::IN_REPAIR->value),
            self::AVAILABLE => trans('book::enum.book_version_status.' . self::AVAILABLE->value),
            self::RESERVED => trans('book::enum.book_version_status.' . self::RESERVED->value),
            self::UN_RESERVABLE => trans('book::enum.book_version_status.' . self::UN_RESERVABLE->value),
            default => 'unknown'
        };
    }
}
