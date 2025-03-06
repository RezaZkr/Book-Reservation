<?php

namespace Modules\Reservation\Interfaces\Api\V1;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Modules\Reservation\Models\Loan;
use Modules\Reservation\Models\Reservation;

interface LoanRepositoryInterface
{
    public function loan(array $data): JsonResponse;

    public function return(array $data): JsonResponse;

    public function getDelayedLoans(): ?Collection;

    public function findById(int $id): ?Loan;

    public function loanByReservationRequest(Reservation $reservation): void;

}
