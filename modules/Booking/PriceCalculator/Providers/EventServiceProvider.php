<?php

namespace Module\Booking\PriceCalculator\Providers;

use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\PriceCalculator\Domain\Listener\RecalculateBookingPricesListener;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class
    ];

//    public function registerListeners($eventDispatcher)
//    {
//        //$domainEventDispatcher->listen(Req::class, StatusChangedListener::class);
//    }
}
