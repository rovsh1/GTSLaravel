<?php

namespace GTS\Reservation\HotelReservation\Application\Event;

use Custom\Framework\Event\DomainEventInterface;
use Custom\Framework\Event\DomainEventListenerInterface;

class ReservationRequestSentListener implements DomainEventListenerInterface
{
    public function handle(DomainEventInterface $event) {}
}
