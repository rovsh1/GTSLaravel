<?php

namespace Module\Booking\Domain\HotelBooking\Event;

use Module\Booking\Domain\HotelBooking\Entity\RoomBooking;
use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Event\AbstractBookingEvent;

class RoomAdded extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        BookingInterface|HotelBooking $booking,
        public readonly RoomBooking $roomBooking,
    ) {
        parent::__construct($booking);
    }
}
