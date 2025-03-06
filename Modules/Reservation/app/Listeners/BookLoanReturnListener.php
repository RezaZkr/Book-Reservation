<?php

namespace Modules\Reservation\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Reservation\Events\BookLoanReturnEvent;
use Modules\Reservation\Jobs\FindBestReservationJob;

class BookLoanReturnListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookLoanReturnEvent $event): void
    {
        FindBestReservationJob::dispatch($event->loan);
    }
}
