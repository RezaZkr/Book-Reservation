<?php

namespace Modules\Reservation\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\General\Enums\QueueEnum;
use Modules\Reservation\Events\BookLoanEvent;
use Modules\Reservation\Notifications\BookLoanNotification;

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
        $event->loan->user->notify((new BookLoanNotification($event->loan))->onQueue(QueueEnum::NOTIFICATIONS));
    }
}
