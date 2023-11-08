<?php

namespace Module\Hotel\Moderation\Providers;

use Sdk\Module\Support\Providers\DomainEventServiceProvider;

class EventServiceProvider extends DomainEventServiceProvider
{
    protected array $listen = [
        //FIXME TEST
        'HotelReservation\ReservationCancelled' => [

        ]
    ];
}
