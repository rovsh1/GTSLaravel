<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\EditEventInterface;
use Module\Booking\Common\Domain\Event\BookingEventInterface;

class GuestAdded implements BookingEventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $guestId,
        public readonly string $guestName
    ) {}
}
