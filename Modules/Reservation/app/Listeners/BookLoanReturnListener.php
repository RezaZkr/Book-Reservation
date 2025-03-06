<?php

namespace Modules\Reservation\Listeners;

use App\Aggregates\BookLoanAggregate;
use Modules\Reservation\Events\BookLoanReturnEvent;
use Modules\Reservation\Jobs\FindBestReservationJob;
use Modules\Reservation\Services\Api\V1\ReservationCacheService;
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
        ReservationCacheService::removeBookVersionFromLoanList($event->loan->branch_id, $event->loan->book_version_id);
        FindBestReservationJob::dispatch($event->loan);
        BookLoanAggregate::retrieve(Uuid::uuid1()->toString())->createBookStateLoanTrack($event->loan)->persist();
    }
}
