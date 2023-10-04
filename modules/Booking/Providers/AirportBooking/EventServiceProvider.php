<?php

namespace Module\Booking\Providers\AirportBooking;

use Module\Booking\Airport\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Airport\Domain\Booking\Listener\RecalculateBookingPricesListener;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
    ];
}
