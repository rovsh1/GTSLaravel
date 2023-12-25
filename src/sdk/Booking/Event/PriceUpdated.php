<?php

namespace Sdk\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Booking\ValueObject\BookingPrices;

final class PriceUpdated extends AbstractBookingEvent
{
    public function __construct(
        Booking $booking,
        public readonly BookingPrices $priceBefore,
    ) {
        parent::__construct($booking);
    }
}
