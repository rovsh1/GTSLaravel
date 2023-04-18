<?php

namespace Module\Booking\Hotel\Application\Event;

use Custom\Framework\Contracts\Event\DomainEventInterface;
use Custom\Framework\Contracts\Event\DomainEventListenerInterface;

class ReservationRequestSentListener implements DomainEventListenerInterface
{
    public function handle(DomainEventInterface $event) {}
}
