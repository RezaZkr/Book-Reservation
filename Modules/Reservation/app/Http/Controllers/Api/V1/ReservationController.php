<?php

namespace Modules\Reservation\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Modules\Reservation\Http\Requests\Api\V1\ReserveRequest;
use Modules\Reservation\Interfaces\Api\V1\ReservationRepositoryInterface;

class ReservationController extends Controller
{
    public function __construct(protected ReservationRepositoryInterface $reservationRepository)
    {
    }

    public function reserve(ReserveRequest $request)
    {
        return $this->reservationRepository->reserve($request->validationData());
    }
}
