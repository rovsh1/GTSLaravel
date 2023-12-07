<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Event\BookingEventInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Event\IntegrationEventMessages;

class GuestBinded implements BookingEventInterface, IntegrationEventInterface
{
    public function __construct(
        public readonly CarBid $carBid,
        public readonly GuestId $guestId,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->carBid->bookingId();
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::TRANSFER_BOOKING_GUEST_ADDED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            'guestId' => $this->guestId->value()
        ];
    }
}
