<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationDetails;

class AccommodationDetailsEdited extends AbstractAccommodationEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        HotelAccommodation $accommodation,
        public readonly AccommodationDetails $detailsBefore,
    ) {
        parent::__construct($accommodation);
    }
}
