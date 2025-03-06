<?php

use Illuminate\Support\Facades\Schedule;
use Modules\General\Enums\QueueEnum;
use Modules\Reservation\Jobs\DetectDelayedLoanJob;


Schedule::job(DetectDelayedLoanJob::class, QueueEnum::DELAYED_LOANS)->dailyAt(1);
