<?php

namespace Module\Integration\Traveline\Providers;

use Module\Integration\Traveline\Application\Event\SendReservationNotificationListener;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'HotelReservation\ReservationCancelled' => [
            SendReservationNotificationListener::class
        ],
        //@todo добавить события новой брои и измененной
    ];

    public function registerListeners($eventDispatcher)
    {
        parent::registerListeners($eventDispatcher);
    }
}
