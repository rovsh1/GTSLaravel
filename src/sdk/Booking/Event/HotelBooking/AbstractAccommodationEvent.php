<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\BookingId;

abstract class AbstractAccommodationEvent implements BookingEventInterface
{
    public function __construct(
        public readonly HotelAccommodation $accommodation,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->accommodation->bookingId();
    }
}
