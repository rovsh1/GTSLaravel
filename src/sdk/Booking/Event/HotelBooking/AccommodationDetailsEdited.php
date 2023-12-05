<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationDetails;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Event\IntegrationEventMessages;

class AccommodationDetailsEdited extends AbstractAccommodationEvent implements PriceBecomeDeprecatedEventInterface, IntegrationEventInterface
{
    public function __construct(
        HotelAccommodation $accommodation,
        public readonly AccommodationDetails $detailsBefore,
    ) {
        parent::__construct($accommodation);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_DETAILS_EDITED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            'detailsBefore' => $this->detailsBefore->serialize(),
            'detailsAfter' => $this->accommodation->details()->serialize()
        ];
    }
}
