<?php

namespace Modules\Reservation\Listeners;

use App\Aggregates\BookLoanAggregate;
use Modules\General\Enums\QueueEnum;
use Modules\Reservation\Events\BookLoanEvent;
use Modules\Reservation\Notifications\BookLoanNotification;
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
        BookLoanAggregate::retrieve(Uuid::uuid1()->toString())->createBookStateLoanTrack($event->loan)->persist();
        $event->loan->user->notify((new BookLoanNotification($event->loan))->onQueue(QueueEnum::NOTIFICATIONS));
    }
}
