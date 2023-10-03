<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Event;

use Module\Booking\Domain\Shared\Entity\BookingInterface;

abstract class AbstractBookingEvent implements BookingEventInterface
{
    public function __construct(
        public readonly BookingInterface $booking,
    ) {}

    public function booking(): BookingInterface
    {
        return $this->booking;
    }

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
