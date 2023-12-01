<?php

declare(strict_types=1);

namespace Sdk\Booking\Support\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\OrderId;

abstract class AbstractBookingEvent implements BookingEventInterface
{
    public function __construct(
        public readonly Booking $booking,
    ) {}

    public function booking(): Booking
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
}
