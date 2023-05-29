<?php

//FIXME TEST
namespace Module\HotelOld\Domain\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;

class ReservationCancelledListener implements IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event)
    {
        dd($event);
    }
}
