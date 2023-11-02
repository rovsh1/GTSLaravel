<?php

namespace Module\Booking\Domain\Booking\Event\HotelBooking;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\Shared\Event\AbstractBookingEvent;

class RoomEdited extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly HotelRoomBooking $roomBooking,
    ) {
        parent::__construct($booking);
    }
}
