<?php

namespace Modules\Reservation\Listeners;

use App\Aggregates\BookLoanAggregate;
use Modules\General\Enums\QueueEnum;
use Modules\Reservation\Events\BookLoanEvent;
use Modules\Reservation\Notifications\BookLoanNotification;
use Modules\Reservation\Services\Api\V1\ReservationCacheService;
use Ramsey\Uuid\Uuid;

class BookLoanListener
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
    public function handle(BookLoanEvent $event): void
    {
        ReservationCacheService::addBookVersionToLoanList($event->loan->branch_id, $event->loan->book_version_id);
        $event->loan->user->notify((new BookLoanNotification($event->loan))->onQueue(QueueEnum::NOTIFICATIONS));
        BookLoanAggregate::retrieve(Uuid::uuid1()->toString())->createBookStateLoanTrack($event->loan)->persist();
    }
}
