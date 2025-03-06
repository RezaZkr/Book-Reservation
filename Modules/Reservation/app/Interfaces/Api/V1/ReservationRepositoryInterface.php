<?php

namespace Modules\Reservation\Interfaces\Api\V1;

use Illuminate\Http\JsonResponse;
use Modules\Reservation\Models\Reservation;

interface ReservationRepositoryInterface
{
    public function reserve(array $data): JsonResponse;

    public function findLowestPenaltyPoint(int $branchId, int $bookVersionId, bool $lock = false): ?Reservation;
}
