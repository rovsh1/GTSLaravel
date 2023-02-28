<?php

namespace Module\Hotel\Domain\Event;

use Custom\Framework\Contracts\Event\IntegrationEventInterface;

class ReservationCancelledListener
{
    public function handle(IntegrationEventInterface $event)
    {
        dd($event);
    }
}
