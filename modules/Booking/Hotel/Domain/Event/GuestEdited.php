<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\EditEventInterface;
use Module\Booking\Common\Domain\Event\BookingEventInterface;

class GuestEdited implements BookingEventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $guestId,
        public readonly string $guestName,
        public readonly string $attribute,
        public readonly mixed $prevValue,
        public readonly mixed $newValue
    ) {}
}
