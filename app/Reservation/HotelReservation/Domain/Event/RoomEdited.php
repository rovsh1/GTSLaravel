<?php

namespace GTS\Reservation\HotelReservation\Domain\Event;

use GTS\Reservation\Common\Domain\Event\EditEventInterface;
use GTS\Reservation\Common\Domain\Event\EventInterface;

class RoomEdited implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly string $roomName,
        public readonly string $attribute,
        public readonly mixed $prevValue,
        public readonly mixed $newValue
    ) {}
}
