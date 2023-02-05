<?php

namespace GTS\Reservation\Domain\Event;

class RoomAdded implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $hotelId,
        public readonly int $roomId,
        public readonly string $roomName
    ) {}
}
