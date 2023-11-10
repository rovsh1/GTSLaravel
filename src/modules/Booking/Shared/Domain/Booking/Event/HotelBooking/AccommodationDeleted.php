<?php

namespace Module\Booking\Shared\Domain\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Booking\Shared\Domain\Shared\Event\AbstractBookingEvent;

class AccommodationDeleted extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface, QuotaChangedEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly AccommodationId $accommodationId,
    ) {
        parent::__construct($booking);
    }
}
