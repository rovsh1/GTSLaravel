<?php

namespace Module\Booking\Hotel\Providers;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Hotel\Domain\Listener\BookingChangesListener;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BookingEventInterface::class => BookingChangesListener::class,
    ];

//    public function registerListeners($eventDispatcher)
//    {
//        //$domainEventDispatcher->listen(Req::class, StatusChangedListener::class);
//    }
}
