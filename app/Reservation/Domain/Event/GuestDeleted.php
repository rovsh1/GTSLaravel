<?php

namespace GTS\Reservation\Domain\Event;

use GTS\Shared\Domain\Event\EventInterface;

class GuestDeleted implements EventInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $guestId,
        public readonly string $guestName,
    ) {}
}
