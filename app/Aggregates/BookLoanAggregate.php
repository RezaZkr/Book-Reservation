<?php

namespace App\Aggregates;

use Modules\Book\Events\BookStateTrackEvent;
use Modules\Reservation\Models\Loan;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class BookLoanAggregate extends AggregateRoot
{
    public function createBookStateLoanTrack(Loan $loan)
    {
        $this->recordThat(new BookStateTrackEvent($loan));
        return $this;
    }
}
