<?php

namespace Module\Booking\Airport\Domain\Event;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Order\Domain\ValueObject\GuestId;

class GuestUnbinded implements BookingEventInterface, PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        public readonly BookingId $bookingId,
        public readonly OrderId $orderId,
        public readonly GuestId $guestId,
    ) {
    }

    public function bookingId(): int
    {
        return $this->bookingId->value();
    }

    public function orderId(): int
    {
        return $this->orderId->value();
    }

    public function payload(): ?array
    {
        return null;
    }
}
