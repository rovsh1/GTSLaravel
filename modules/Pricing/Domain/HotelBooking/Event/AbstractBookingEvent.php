<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\HotelBooking\Event;

use Module\Pricing\Domain\HotelBooking\Booking;
use Sdk\Module\Contracts\Event\DomainEventInterface;

abstract class AbstractBookingEvent implements DomainEventInterface
{
    public function __construct(
        public readonly Booking $booking,
    ) {}

    public function booking(): Booking
    {
        return $this->booking;
    }

    public function bookingId(): int
    {
        return $this->booking->id()->value();
    }

    public function payload(): ?array
    {
        return null;
    }
}
