<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Providers;

use GTS\Reservation\Common\Domain\Event\StatusEventInterface;
use GTS\Reservation\HotelReservation\Application\Event\StatusChangedListener;
use GTS\Shared\Infrastructure\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register() {

        //$domainEventDispatcher->listen(StatusEventInterface::class, StatusChangedListener::class);
        //$domainEventDispatcher->listen(Req::class, StatusChangedListener::class);
    }
}
