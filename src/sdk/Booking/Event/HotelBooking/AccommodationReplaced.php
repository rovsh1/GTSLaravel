<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\QuotaChangedEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationReplaced as IntegrationEvent;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

class AccommodationReplaced extends AbstractAccommodationEvent implements
    QuotaChangedEventInterface,
    PriceBecomeDeprecatedEventInterface,
    HasIntegrationEventInterface,
    InvoiceBecomeDeprecatedEventInterface
{
    public function __construct(
        HotelAccommodation $accommodation,
        public readonly HotelAccommodation $accommodationBefore,
    ) {
        parent::__construct($accommodation);
    }

    public function integrationEvent(): IntegrationEventInterface
    {
        return new IntegrationEvent(
            $this->bookingId()->value(),
            $this->accommodation->id()->value(),
            $this->accommodation->roomInfo()->id(),
            $this->accommodation->roomInfo()->name(),
            $this->accommodationBefore->roomInfo()->name(),
        );
    }
}
