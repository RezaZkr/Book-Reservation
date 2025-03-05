<?php

namespace Modules\Reservation\Services\Api\V1;

use Illuminate\Support\Facades\Redis;
use Modules\Reservation\Enums\ReservationCacheEnum;

class ReservationCacheService
{
    public static function addBookVersionToLoanList(int $branchId, int $bookVersionId): void
    {
        if (self::hasBookVersionInLoanList($branchId, $bookVersionId)) {
            return;
        }

        $cacheKey = ReservationCacheEnum::LOAN_LIST . $branchId;

        //important note add new item to list if not exists
        Redis::sAdd($cacheKey, $bookVersionId);
    }

    public static function removeBookVersionFromLoanList(int $branchId, int $bookVersionId): void
    {
        $cacheKey = ReservationCacheEnum::LOAN_LIST . $branchId;
        Redis::srem($cacheKey, $bookVersionId);
    }

    public static function hasBookVersionInLoanList(int $branchId, int $bookVersionId): bool
    {
        $cacheKey = ReservationCacheEnum::LOAN_LIST . $branchId;
        return Redis::sismember($cacheKey, $bookVersionId);
    }

    public static function getLoanListFromBranch(int $branchId): array
    {
        //important note return array() if empty
        return Redis::sMembers(ReservationCacheEnum::LOAN_LIST . $branchId);
    }
}
