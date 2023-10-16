<?php

namespace Module\Booking\Domain\Booking\Event\HotelBooking;

use Module\Booking\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\Event\BookingEventInterface;
use Module\Booking\Domain\Shared\ValueObject\GuestId;

class GuestUnbinded implements BookingEventInterface, PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        public readonly BookingId $bookingId,
        public readonly OrderId $orderId,
        public readonly RoomBookingId $roomBookingId,
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
