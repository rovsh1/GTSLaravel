<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\QuotaChangedEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Shared\Event\IntegrationEventMessages;

class AccommodationReplaced extends AbstractAccommodationEvent implements QuotaChangedEventInterface
{
    public function __construct(
        HotelAccommodation $accommodation,
        public readonly HotelAccommodation $accommodationBefore,
    ) {
        parent::__construct($accommodation);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REPLACED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            'before' => $this->accommodationBefore->serialize(),
            'after' => $this->accommodation->serialize()
        ];
    }
}
