<?php

namespace Module\Booking\Moderation\Domain\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelAccommodation;
use Module\Booking\Shared\Domain\Booking\Event\AbstractBookingEvent;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;

class AccommodationEdited extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly HotelAccommodation $accommodation,
    ) {
        parent::__construct($booking);
    }
}
