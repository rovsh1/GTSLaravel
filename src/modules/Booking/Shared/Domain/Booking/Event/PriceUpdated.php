<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;

final class PriceUpdated extends AbstractBookingEvent
{
    public function __construct(
        Booking $booking,
        public readonly BookingPrices $priceBefore,
    ) {
        parent::__construct($booking);
    }
}
