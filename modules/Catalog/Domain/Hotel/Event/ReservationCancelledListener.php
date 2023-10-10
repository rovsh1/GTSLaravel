<?php

//FIXME TEST
namespace Module\Catalog\Domain\Hotel\Event;

use Module\Shared\Contracts\Event\IntegrationEventInterface;

class ReservationCancelledListener implements \Module\Shared\Contracts\Event\IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event)
    {
        dd($event);
    }
}
