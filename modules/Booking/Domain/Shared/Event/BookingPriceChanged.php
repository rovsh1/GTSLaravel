<?php

namespace Module\Booking\Domain\Shared\Event;

use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;

class BookingPriceChanged extends AbstractBookingEvent
{
    public function __construct(
        BookingInterface $booking,
        public readonly BookingPrice $priceBefore,
    ) {
        parent::__construct($booking);
    }
}
