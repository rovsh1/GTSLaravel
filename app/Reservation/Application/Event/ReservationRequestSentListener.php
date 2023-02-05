<?php

namespace GTS\Reservation\Application\Event;

use GTS\Shared\Application\Event\EventListenerInterface;
use GTS\Shared\Domain\Event\EventInterface;

class ReservationRequestSentListener implements EventListenerInterface
{
    public function handle(EventInterface $event) {}
}
