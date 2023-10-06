<?php

namespace Module\Booking\Domain\ServiceBooking\ValueObject;

use Sdk\Module\Support\AbstractValueObjectCollection;

class GuestIdCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof GuestId) {
            throw new \InvalidArgumentException(GuestId::class . ' required');
        }
    }
}