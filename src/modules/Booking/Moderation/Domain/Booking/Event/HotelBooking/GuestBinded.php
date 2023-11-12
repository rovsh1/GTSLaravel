<?php

namespace Module\Booking\Moderation\Domain\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Event\BookingEventInterface;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;

class GuestBinded implements BookingEventInterface, PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        public readonly BookingId $bookingId,
        public readonly OrderId $orderId,
        public readonly AccommodationId $accommodationId,
        public readonly GuestId $guestId
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
