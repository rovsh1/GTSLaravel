<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Shared\Event\IntegrationEventMessages;

class GuestUnbinded extends AbstractAccommodationEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        HotelAccommodation $accommodation,
        public readonly GuestId $guestId
    ) {
        parent::__construct($accommodation);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::HOTEL_BOOKING_GUEST_REMOVED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            'accommodationId' => $this->accommodation->id()->value(),
            'guestId' => $this->guestId->value()
        ];
    }
}
