<?php

namespace Modules\Reservation\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Reservation\Events\BookLoanEvent;
use Modules\Reservation\Events\BookLoanReturnEvent;
use Modules\Reservation\Listeners\BookLoanListener;
use Modules\Reservation\Listeners\BookLoanReturnListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        BookLoanEvent::class       => [
            BookLoanListener::class,
        ],
        BookLoanReturnEvent::class => [
            BookLoanReturnListener::class
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void
    {
        //
    }
}
