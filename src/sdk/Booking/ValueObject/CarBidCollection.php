<?php

namespace Sdk\Booking\ValueObject;

use Sdk\Booking\Entity\CarBid;
use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, CarBid>
 */
class CarBidCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof CarBid) {
            throw new \InvalidArgumentException(CarBid::class . ' required');
        }
    }
}
