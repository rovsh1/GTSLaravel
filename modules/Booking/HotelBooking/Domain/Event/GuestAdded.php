<?php

namespace Module\Booking\HotelBooking\Domain\Event;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\Guest;

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
        return $this->roomBooking->orderId()->value();
    }

    public function payload(): ?array
    {
        return null;
    }
}