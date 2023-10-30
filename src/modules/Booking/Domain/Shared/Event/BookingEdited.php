<?php

namespace Module\Booking\Domain\Shared\Event;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\Entity\AbstractBooking;

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
