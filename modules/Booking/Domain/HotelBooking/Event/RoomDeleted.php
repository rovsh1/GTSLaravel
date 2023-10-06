<?php

namespace Module\Booking\Domain\HotelBooking\Event;

use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Event\AbstractBookingEvent;

class RoomDeleted extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        BookingInterface|HotelBooking $booking,
        public readonly RoomBookingId $roomBookingId,
    ) {
        parent::__construct($booking);
    }
}
