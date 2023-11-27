<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\OrderId;

class BookingDetailsCreated implements BookingEventInterface, PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        private readonly BookingId $bookingId,
        private readonly OrderId $orderId
    ) {}

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }
}
