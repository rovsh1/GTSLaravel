<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Event\IntegrationEventMessages;

class GuestBinded extends AbstractAccommodationEvent implements PriceBecomeDeprecatedEventInterface,
                                                                IntegrationEventInterface
{
    public function __construct(
        public readonly HotelAccommodation $accommodation,
        public readonly GuestId $guestId
    ) {
        parent::__construct($this->accommodation);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::HOTEL_BOOKING_GUEST_ADDED;
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
