<?php

namespace Module\Reservation\HotelReservation\Domain\Event;

use Module\Reservation\Common\Domain\Event\EventInterface;
use Module\Reservation\Common\Domain\Event\StatusEventInterface;

class ReservationCancelled implements EventInterface, StatusEventInterface
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $cancellationNote = null,
    ) {}
}
