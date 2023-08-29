<?php

namespace Module\Booking\HotelBooking\Domain\Event;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Order\Domain\ValueObject\TouristId;

class GuestBinded implements BookingEventInterface, PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        public readonly BookingId $bookingId,
        public readonly OrderId $orderId,
        public readonly RoomBookingId $roomBookingId,
        public readonly TouristId $touristId
    ) {}

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
