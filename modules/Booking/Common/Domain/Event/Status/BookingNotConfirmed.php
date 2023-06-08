<?php

namespace Module\Booking\Common\Domain\Event\Status;

use Module\Booking\Common\Domain\Entity\Booking;
use Module\Booking\Common\Domain\Event\StatusBookingEventInterface;

class BookingNotConfirmed implements StatusBookingEventInterface
{
    public function __construct(
        public readonly Booking $booking,
        public readonly string $reason
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
