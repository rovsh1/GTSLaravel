<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\EditEventInterface;
use Module\Booking\Common\Domain\Event\BookingEventInterface;

class RoomEdited implements BookingEventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly string $roomName,
        public readonly string $attribute,
        public readonly mixed $prevValue,
        public readonly mixed $newValue
    ) {}
}
