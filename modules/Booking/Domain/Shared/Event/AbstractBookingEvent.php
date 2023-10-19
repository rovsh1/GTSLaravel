<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Event;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Order\ValueObject\OrderId;
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

    public function bookingId(): BookingId
    {
        return $this->booking->id();
    }

    public function orderId(): OrderId
    {
        return $this->booking->orderId();
    }

    public function payload(): ?array
    {
        return null;
    }
}
