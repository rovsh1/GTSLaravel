<?php

namespace Module\Booking\Common\Domain\Event;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\ValueObject\BookingPriceNew;

class BookingPriceChanged extends AbstractBookingEvent
{
    public function __construct(
        BookingInterface $booking,
        public readonly BookingPriceNew $priceBefore,
    ) {
        parent::__construct($booking);
    }
}
