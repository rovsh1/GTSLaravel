<?php

namespace Module\Booking\Common\Domain\Event;

use Module\Booking\Common\Domain\Entity\Booking;

class BookingRequestSent implements RequestBookingEventInterface
{
    public function __construct(
        public readonly Booking $booking,
        public readonly int $requestId,
    ) {}

    public function bookingId(): int
    {
        return $this->booking->id()->value();
    }

    public function orderId(): int
    {
        return $this->booking->orderId()->value();
    }
}
