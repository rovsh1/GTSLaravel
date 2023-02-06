<?php

namespace GTS\Reservation\HotelReservation\Domain\Event;

use GTS\Reservation\Common\Domain\Event\EditEventInterface;
use GTS\Reservation\Common\Domain\Event\EventInterface;

class GuestDeleted implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $guestId,
        public readonly string $guestName,
    ) {}
}
