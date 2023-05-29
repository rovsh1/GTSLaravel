<?php

//FIXME TEST
namespace Module\Hotel\Domain\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;

class ReservationCancelledListener implements \Sdk\Module\Contracts\Event\IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event)
    {
        dd($event);
    }
}
