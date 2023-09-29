<?php

namespace Module\Booking\Airport\Providers;

use Module\Booking\Airport\Domain\Listener\RecalculateBookingPricesListener;
use Module\Booking\Airport\Domain\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
    ];
}
