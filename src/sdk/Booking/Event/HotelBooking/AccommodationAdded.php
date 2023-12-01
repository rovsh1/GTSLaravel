<?php

namespace Sdk\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\QuotaChangedEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Shared\Event\IntegrationEventMessages;

class AccommodationAdded extends AbstractAccommodationEvent implements QuotaChangedEventInterface
{
    public function __construct(
        HotelAccommodation $accommodation,
    ) {
        parent::__construct($accommodation);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_ADDED;
    }

    public function integrationPayload(): array
    {
        return $this->accommodation->serialize();
    }
}
