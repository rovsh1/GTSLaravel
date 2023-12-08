<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\QuotaChangedEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationDeleted as IntegrationEvent;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;

class AccommodationDeleted extends AbstractAccommodationEvent implements QuotaChangedEventInterface,
                                                                         PriceBecomeDeprecatedEventInterface,
                                                                         HasIntegrationEventInterface
{
    public function __construct(
        HotelAccommodation $accommodation,
    ) {
        parent::__construct($accommodation);
    }

    public function integrationEvent(): IntegrationEvent
    {
        return new IntegrationEvent(
            $this->bookingId()->value(),
            $this->accommodation->id()->value(),
            $this->accommodation->roomInfo()->id(),
            $this->accommodation->roomInfo()->name(),
        );
    }
}
