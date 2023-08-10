<?php

namespace Module\Booking\HotelBooking\Domain\Event;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Event\AbstractBookingEvent;
use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\HotelBooking\Domain\Entity\Booking;

class RoomEdited extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface, QuotaAffectEventInterface
{
    public function __construct(
        BookingInterface|Booking $booking,
        public readonly int $roomId,
        public readonly string $roomName,
        public readonly string $attribute,
        public readonly mixed $prevValue,
        public readonly mixed $newValue
    ) {
        parent::__construct($booking);
    }
}
