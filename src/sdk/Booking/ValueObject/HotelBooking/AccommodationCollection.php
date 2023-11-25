<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject\HotelBooking;

use Illuminate\Support\Collection;
use Sdk\Booking\Entity\BookingDetails\HotelAccommodation;
use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends Collection<int, HotelAccommodation>
 */
final class AccommodationCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof HotelAccommodation) {
            throw new \InvalidArgumentException(HotelAccommodation::class . ' instance required');
        }
    }
}
