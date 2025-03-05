<?php

namespace Modules\Book\Enums;

enum BookVersionConditionEnum: string
{
    case NEW = 'new';
    case MODERATELY_USED = 'moderately_used';
    case DAMAGED = 'damaged';
    case UNUSABLE = 'unusable';

    public function label(): string
    {
        return match ($this) {
            self::NEW => trans('book::enum.book_version_condition.' . self::NEW->value),
            self::MODERATELY_USED => trans('book::enum.book_version_condition.' . self::MODERATELY_USED->value),
            self::DAMAGED => trans('book::enum.book_version_condition.' . self::DAMAGED->value),
            self::UNUSABLE => trans('book::enum.book_version_condition.' . self::UNUSABLE->value),
            default => 'unknown'
        };
    }
}
