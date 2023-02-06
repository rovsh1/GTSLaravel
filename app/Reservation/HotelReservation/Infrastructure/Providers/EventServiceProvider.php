<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Providers;

use GTS\Reservation\Common\Domain\Event\StatusEventInterface;
use GTS\Reservation\HotelReservation\Application\Event\StatusChangedListener;
use GTS\Shared\Infrastructure\Support\EventServiceProvider as ServiceProvider;

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
