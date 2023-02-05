<?php

namespace GTS\Reservation\HotelReservation\Domain\Event;

use GTS\Reservation\Common\Domain\Event\EditEventInterface;
use GTS\Reservation\Common\Domain\Event\EventInterface;

class RoomDeleted implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $hotelId,
        public readonly int $roomId,
        public readonly string $roomName,
    ) {}
}
