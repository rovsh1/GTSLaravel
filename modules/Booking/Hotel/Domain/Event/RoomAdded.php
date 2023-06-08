<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\EditEventInterface;
use Module\Booking\Common\Domain\Event\BookingEventInterface;

class RoomAdded implements BookingEventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $hotelId,
        public readonly int $roomId,
        public readonly string $roomName
    ) {}
}
