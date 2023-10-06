<?php

namespace Module\Pricing\Domain\HotelBooking\Event;

use Module\Pricing\Domain\HotelBooking\Booking;
use Module\Pricing\Domain\HotelBooking\ValueObject\BookingPrice;

class BookingPriceChanged extends AbstractBookingEvent
{
    public function __construct(
        Booking $booking,
        public readonly BookingPrice $priceBefore,
    ) {
        parent::__construct($booking);
    }
}
