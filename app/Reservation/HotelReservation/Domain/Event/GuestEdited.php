<?php

namespace GTS\Reservation\HotelReservation\Domain\Event;

use GTS\Reservation\Common\Domain\Event\EditEventInterface;
use GTS\Reservation\Common\Domain\Event\EventInterface;

class GuestEdited implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $guestId,
        public readonly string $guestName,
        public readonly string $attribute,
        public readonly mixed $prevValue,
        public readonly mixed $newValue
    ) {}
}
