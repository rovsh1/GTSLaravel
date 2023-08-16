<?php

namespace Module\Booking\HotelBooking\Domain\Event;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Event\AbstractBookingEvent;
use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;

class RoomDeleted extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface, QuotaAffectEventInterface
{
    public function __construct(
        BookingInterface|Booking $booking,
        public readonly RoomBookingId $roomBookingId,
    ) {
        parent::__construct($booking);
    }
}
