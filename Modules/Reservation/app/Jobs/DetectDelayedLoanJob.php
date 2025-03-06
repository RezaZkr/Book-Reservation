<?php

namespace Modules\Reservation\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Reservation\Interfaces\Api\V1\LoanRepositoryInterface;
use Modules\User\Jobs\UpdateUserPenaltyPointJob;

class DetectDelayedLoanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(LoanRepositoryInterface $loanRepository): void
    {
        foreach ($loanRepository->getDelayedLoans() as $loan) {
            UpdateUserPenaltyPointJob::dispatch($loan->id);
        }
    }
}
