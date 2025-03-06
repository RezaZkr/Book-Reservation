<?php

namespace Modules\Reservation\Listeners;

use App\Aggregates\BookLoanAggregate;
use Modules\Reservation\Events\BookLoanReturnEvent;
use Modules\Reservation\Jobs\FindBestReservationJob;
use Ramsey\Uuid\Uuid;

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
        BookLoanAggregate::retrieve(Uuid::uuid1()->toString())->createBookStateLoanTrack($event->loan)->persist();
        FindBestReservationJob::dispatch($event->loan);
    }
}
