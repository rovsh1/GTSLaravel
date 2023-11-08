<?php

namespace Module\Booking\Shared\Domain\Shared\Event;

use Module\Booking\Domain\Shared\Entity\AbstractBooking;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;

class BookingEdited implements BookingEventInterface
{
    public function __construct(public readonly AbstractBooking $booking) {}

    public function bookingId(): BookingId
    {
        return $this->booking->id();
    }

    public function orderId(): OrderId
    {
        return $this->booking->orderId();
    }
}
