<?php

namespace Module\Reservation\HotelReservation\Providers;

use Custom\Framework\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Module\Reservation\Common\Domain\Event\StatusEventInterface;
use Module\Reservation\HotelReservation\Application\Event\StatusChangedListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StatusEventInterface::class => StatusChangedListener::class
    ];

    public function registerListeners($eventDispatcher)
    {
        //$domainEventDispatcher->listen(Req::class, StatusChangedListener::class);
    }
}
