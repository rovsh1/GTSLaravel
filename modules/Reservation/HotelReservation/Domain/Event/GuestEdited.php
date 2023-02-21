<?php

namespace Module\Reservation\HotelReservation\Domain\Event;

use Module\Reservation\Common\Domain\Event\EditEventInterface;
use Module\Reservation\Common\Domain\Event\EventInterface;

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
