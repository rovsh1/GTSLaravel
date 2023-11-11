<?php

namespace Module\Booking\Moderation\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\Event\BookingEventInterface;

class CarBidAdded implements BookingEventInterface, PriceBecomeDeprecatedEventInterface
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
