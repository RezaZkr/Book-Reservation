<?php

namespace Modules\User\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\User\Interfaces\Api\V1\UserRepositoryInterface;

class UpdateUserPenaltyPointJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected int $loanId)
    {
        //
    }

    public function handle(UserRepositoryInterface $userRepository): void
    {
        $userRepository->updateDailyPenaltyPoint($this->loanId);
    }

}
