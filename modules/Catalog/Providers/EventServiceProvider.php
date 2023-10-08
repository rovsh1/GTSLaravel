<?php

namespace Module\Catalog\Providers;

use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
