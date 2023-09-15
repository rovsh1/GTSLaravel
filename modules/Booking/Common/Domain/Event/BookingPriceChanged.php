<?php

namespace Module\Booking\Common\Domain\Event;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;

class BookingPriceChanged extends AbstractBookingEvent
{
    public function __construct(
        BookingInterface $booking,
        public readonly BookingPrice $priceBefore,
    ) {
        parent::__construct($booking);
    }
}
