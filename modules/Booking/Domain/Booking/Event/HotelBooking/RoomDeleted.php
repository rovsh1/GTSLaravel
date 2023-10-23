<?php

namespace Module\Booking\Domain\Booking\Event\HotelBooking;

use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
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