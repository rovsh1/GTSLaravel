<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Event\AbstractBookingEvent;
use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Hotel\Domain\Entity\Booking;

class RoomAdded extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        BookingInterface|Booking $booking,
        public readonly int $hotelId,
        public readonly int $roomId,
        public readonly string $roomName
    ) {
        parent::__construct($booking);
    }
}
