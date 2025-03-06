<?php

namespace Modules\Reservation\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Reservation\Interfaces\Api\V1\LoanRepositoryInterface;
use Modules\Reservation\Interfaces\Api\V1\ReservationRepositoryInterface;
use Modules\Reservation\Models\Loan;

class FindBestReservationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Loan $loan)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ReservationRepositoryInterface $reservationRepository, LoanRepositoryInterface $loanRepository): void
    {
        if (!$reservationRequest = $reservationRepository->findLowestPenaltyPoint($this->loan->branch_id, $this->loan->book_version_id,true)) {
            return;
        }
        $loanRepository->loanByReservationRequest($reservationRequest);
    }
}
