<?php

namespace Module\HotelOld\Providers;

use Module\HotelOld\Domain\Event\ReservationCancelledListener;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
