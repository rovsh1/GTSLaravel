<?php

namespace GTS\Reservation\Domain\Event;

use GTS\Shared\Domain\Event\EventInterface;

class RoomDeleted implements EventInterface
{
    public function __construct(
        public readonly int $hotelId,
        public readonly int $roomId,
        public readonly string $roomName,
    ) {}
}
