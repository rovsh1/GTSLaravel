<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Hotel\Domain\Entity\RoomBooking;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\Guest;

class GuestAdded implements BookingEventInterface, PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        public readonly RoomBooking $roomBooking,
        public readonly Guest $guest
    ) {}

    public function bookingId(): int
    {
        return $this->roomBooking->bookingId()->value();
    }

    public function orderId(): int
    {
        // TODO: Implement orderId() method.
    }

    public function payload(): ?array
    {
        return null;
    }
}
