<?php

namespace Module\Hotel\Providers;

use Custom\Framework\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        //FIXME TEST
        'HotelReservation\ReservationCancelled' => [

        ]
    ];

    public function registerListeners($eventDispatcher)
    {
        parent::registerListeners($eventDispatcher);
    }
}
