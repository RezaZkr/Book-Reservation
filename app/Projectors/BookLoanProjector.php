<?php

namespace App\Projectors;

use Modules\Book\Events\BookStateTrackEvent;
use Modules\Book\Models\BookStateTrack;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class BookLoanProjector extends Projector
{
    public function onBookLoan(BookStateTrackEvent $event)
    {
        BookStateTrack::query()->create([
            'branch_id'       => $event->loan->branch_id,
            'book_version_id' => $event->loan->book_version_id,
            'user_id'         => $event->loan->user_id,
            'status'          => $event->loan->status,
        ]);
    }
}
