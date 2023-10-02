<?php

namespace Module\Booking\HotelBooking\Domain\Event;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Event\AbstractBookingEvent;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;

class RoomAdded extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        BookingInterface|Booking $booking,
        public readonly RoomBooking $roomBooking,
    ) {
        parent::__construct($booking);
    }
}
