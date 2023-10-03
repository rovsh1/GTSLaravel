<?php

namespace Module\Booking\Domain\Shared\Event;

use Module\Booking\Domain\Shared\Entity\AbstractBooking;

class BookingEdited implements BookingEventInterface
{
    public function __construct(public readonly AbstractBooking $booking) {}

    public function bookingId(): int
    {
        return $this->booking->id()->value();
    }

    public function orderId(): int
    {
        return $this->booking->orderId()->value();
    }
}
