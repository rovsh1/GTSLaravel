<?php

//FIXME TEST
namespace Module\Catalog\Domain\Hotel\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;

class ReservationCancelledListener implements \Sdk\Module\Contracts\Event\IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event)
    {
        dd($event);
    }
}
