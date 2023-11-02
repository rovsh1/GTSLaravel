<?php

namespace Module\Generic\Notification\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Generic\Notification\Booking\Domain\Listener\BookingCreatedListener;
use Module\Shared\IntegrationEvent\Booking\BookingCreated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BookingCreated::class => BookingCreatedListener::class
    ];

    public function register()
    {

    }
}
