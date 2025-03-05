<?php

namespace Modules\Reservation\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Modules\Reservation\Http\Requests\Api\V1\LoanRequest;
use Modules\Reservation\Http\Requests\Api\V1\LoanReturnRequest;
use Modules\Reservation\Interfaces\Api\V1\LoanRepositoryInterface;

class LoanController extends Controller
{
    public function __construct(protected LoanRepositoryInterface $loanRepository)
    {
    }

    public function loan(LoanRequest $request)
    {
        return $this->loanRepository->loan($request->validationData());
    }

    public function return(LoanReturnRequest $request)
    {
        return $this->loanRepository->return($request->validationData());
    }
}
