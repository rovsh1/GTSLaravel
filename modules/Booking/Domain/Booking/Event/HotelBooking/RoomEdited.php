<?php

namespace Module\Booking\Domain\Booking\Event\HotelBooking;

use Module\Booking\Deprecated\HotelBooking\Entity\RoomBooking;
use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Event\AbstractBookingEvent;

class RoomEdited extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        BookingInterface|HotelBooking $booking,
        public readonly RoomBooking $roomBooking,
    ) {
        parent::__construct($booking);
    }
}
