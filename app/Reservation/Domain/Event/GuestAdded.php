<?php

namespace GTS\Reservation\Domain\Event;

class GuestAdded implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $guestId,
        public readonly string $guestName
    ) {}
}
