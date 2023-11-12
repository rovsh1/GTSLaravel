<?php

namespace Module\Booking\Moderation\Domain\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelAccommodation;
use Module\Booking\Shared\Domain\Booking\Event\AbstractBookingEvent;
use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\QuotaChangedEventInterface;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;

class AccommodationReplaced extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface,
                                                                    QuotaChangedEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly HotelAccommodation $accommodation,
        public readonly HotelAccommodation $beforeAccommodation,
    ) {
        parent::__construct($booking);
    }
}
