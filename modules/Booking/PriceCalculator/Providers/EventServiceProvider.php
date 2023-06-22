<?php

namespace Module\Booking\PriceCalculator\Providers;

use Module\Booking\Common\Domain\Event\CalculationChangesEventInterface;
use Module\Booking\PriceCalculator\Application\Event\CalculateBookingPricesListener;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CalculationChangesEventInterface::class => CalculateBookingPricesListener::class
    ];

//    public function registerListeners($eventDispatcher)
//    {
//        //$domainEventDispatcher->listen(Req::class, StatusChangedListener::class);
//    }
}
