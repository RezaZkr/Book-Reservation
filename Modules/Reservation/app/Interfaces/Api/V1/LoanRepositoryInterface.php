<?php
namespace Modules\Reservation\Interfaces\Api\V1;

use Illuminate\Http\JsonResponse;

interface LoanRepositoryInterface
{
    public function loan(array $data): JsonResponse;

    public function return(array $data): JsonResponse;
}
