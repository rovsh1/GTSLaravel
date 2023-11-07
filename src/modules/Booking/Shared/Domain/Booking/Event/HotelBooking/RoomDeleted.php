<?php

namespace Module\Booking\Shared\Domain\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Shared\Domain\Shared\Event\AbstractBookingEvent;

class RoomDeleted extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly RoomBookingId $roomBookingId,
    ) {
        parent::__construct($booking);
    }
}
