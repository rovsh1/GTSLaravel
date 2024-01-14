<?php

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\QuotaChangedEventInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\GuestId;

class GuestBinded extends AbstractAccommodationEvent implements PriceBecomeDeprecatedEventInterface,
                                                                QuotaChangedEventInterface
{
    public function __construct(
        HotelAccommodation $accommodation,
        public readonly GuestId $guestId
    ) {
        parent::__construct($accommodation);
    }
}
