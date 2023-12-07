<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\GuestId;

class GuestUnbinded extends AbstractAccommodationEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        HotelAccommodation $accommodation,
        public readonly GuestId $guestId
    ) {
        parent::__construct($accommodation);
    }
}
