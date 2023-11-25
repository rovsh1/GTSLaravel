<?php

namespace Module\Booking\Moderation\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\OrderId;

class CarBidUpdated implements BookingEventInterface, PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        public readonly BookingId $bookingId,
        public readonly OrderId $orderId,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function payload(): ?array
    {
        return null;
    }
}
