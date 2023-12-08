<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\BookingId;

abstract class AbstractCarBidEvent implements BookingEventInterface
{
    public function __construct(
        public readonly CarBid $carBid,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->carBid->bookingId();
    }
}
