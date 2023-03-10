<?php

namespace Module\Integration\Traveline\Providers;

use Custom\Framework\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Module\Integration\Traveline\Application\Event\SendReservationNotificationListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'HotelReservation\ReservationCancelled' => [
            SendReservationNotificationListener::class
        ],
        //todo добавить события новой брои и измененной
    ];

    public function registerListeners($eventDispatcher)
    {
        parent::registerListeners($eventDispatcher);
    }
}
