<?php

namespace Module\Hotel\Providers;

use Custom\Framework\Contracts\Event\IntegrationEventDispatcherInterface;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Hotel\Domain\Event\ReservationCancelledListener;

class EventServiceProvider extends ServiceProvider
{
    public function register()
    {
        $events = $this->app->get(IntegrationEventDispatcherInterface::class);

        $events->listen('HotelReservation\ReservationCancelled', ReservationCancelledListener::class);
    }
}
