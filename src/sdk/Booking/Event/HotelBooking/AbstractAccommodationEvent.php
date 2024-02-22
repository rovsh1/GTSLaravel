<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\AccommodationEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\BookingId;

abstract class AbstractAccommodationEvent implements AccommodationEventInterface
{
    public function __construct(
        public readonly HotelAccommodation $accommodation,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->accommodation->bookingId();
    }
}
