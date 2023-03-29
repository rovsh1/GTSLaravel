<?php

namespace Module\HotelOld\Providers;

use Custom\Framework\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Module\HotelOld\Domain\Event\ReservationCancelledListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        //FIXME TEST
        'HotelReservation\ReservationCancelled' => [
            ReservationCancelledListener::class
        ]
    ];

    public function registerListeners($eventDispatcher)
    {
        parent::registerListeners($eventDispatcher);
    }
}
