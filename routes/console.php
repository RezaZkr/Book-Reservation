<?php

use Illuminate\Support\Facades\Schedule;
use Modules\Reservation\Jobs\DetectDelayedLoanJob;
use Modules\General\Enums\QueueEnum;


Schedule::job(DetectDelayedLoanJob::class, QueueEnum::DELAYED_LOANS)->everyFiveSeconds();
