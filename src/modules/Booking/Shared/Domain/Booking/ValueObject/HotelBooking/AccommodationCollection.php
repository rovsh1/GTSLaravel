<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking;

use Illuminate\Support\Collection;
use Module\Booking\Shared\Domain\Booking\Entity\HotelAccommodation;
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
