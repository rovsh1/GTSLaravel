<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;

class GuestBinded implements BookingEventInterface
{
    public function __construct(
        public readonly CarBid $carBid,
        public readonly GuestId $guestId,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->carBid->bookingId();
    }
}
