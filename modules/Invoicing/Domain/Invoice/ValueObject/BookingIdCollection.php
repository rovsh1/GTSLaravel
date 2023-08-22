<?php

namespace Module\Invoicing\Domain\Invoice\ValueObject;

use Sdk\Module\Support\AbstractItemCollection;

class BookingIdCollection extends AbstractItemCollection
{
    protected function validateItem($item): void
    {
        if (!$item instanceof BookingId) {
            throw new \InvalidArgumentException('');
        }
    }
}