<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Event\AbstractBookingEvent;
use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\Entity\RoomBooking;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\Guest;

class GuestEdited implements BookingEventInterface, PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        public readonly RoomBooking $roomBooking,
        public readonly Guest $guest,
        public readonly string $attribute,
        public readonly mixed $prevValue,
        public readonly mixed $newValue
    ) {
    }

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
