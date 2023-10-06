<?php

namespace Module\Booking\Domain\ServiceBooking\ValueObject;

use Sdk\Module\Support\AbstractValueObjectCollection;

class CarBidCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof CarBid) {
            throw new \InvalidArgumentException(CarBid::class . ' required');
        }
    }
}