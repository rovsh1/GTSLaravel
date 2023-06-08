<?php

namespace Module\Booking\Common\Domain\Event;

use Module\Booking\Common\Domain\Entity\Booking;

class BookingDeleted implements BookingEventInterface, EditEventInterface
{
    public function __construct(public readonly Booking $booking) {}

    public function bookingId(): int
    {
        return $this->booking->id()->value();
    }

    public function orderId(): int
    {
        return $this->booking->orderId()->value();
    }
}
