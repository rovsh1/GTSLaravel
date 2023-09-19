<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Invoice\ValueObject;

use Module\Booking\Common\Domain\ValueObject\OrderId;
use Sdk\Module\Support\AbstractValueObjectCollection;

final class OrderIdCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof OrderId) {
            throw new \InvalidArgumentException();
        }
    }
}
