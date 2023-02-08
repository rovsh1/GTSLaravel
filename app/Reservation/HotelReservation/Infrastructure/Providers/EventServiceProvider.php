<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Providers;

use Custom\Framework\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use GTS\Reservation\Common\Domain\Event\StatusEventInterface;
use GTS\Reservation\HotelReservation\Application\Event\StatusChangedListener;

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
