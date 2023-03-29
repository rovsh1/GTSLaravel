<?php

//FIXME TEST
namespace Module\HotelOld\Domain\Event;

use Custom\Framework\Contracts\Event\IntegrationEventInterface;
use Custom\Framework\Contracts\Event\IntegrationEventListenerInterface;

class ReservationCancelledListener implements IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event)
    {
        dd($event);
    }
}
