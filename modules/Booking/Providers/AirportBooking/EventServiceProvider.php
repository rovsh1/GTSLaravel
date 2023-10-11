<?php

namespace Module\Booking\Providers\AirportBooking;

use Module\Booking\Deprecated\AirportBooking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Deprecated\AirportBooking\Listener\RecalculateBookingPricesListener;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
    ];
}
