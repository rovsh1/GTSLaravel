<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Event;

use Module\Booking\Common\Domain\Entity\AbstractBooking;

abstract class AbstractBookingEvent implements BookingEventInterface
{
    public function __construct(
        public readonly AbstractBooking $booking,
    ) {}

    public function bookingId(): int
    {
        return $this->booking->id()->value();
    }

    public function orderId(): int
    {
        return $this->booking->orderId()->value();
    }

    public function payload(): ?array
    {
        return null;
    }
}
