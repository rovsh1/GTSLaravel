<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\ValueObject;


use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, TouristId>
 */
class TouristIdsCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof TouristId) {
            throw new \InvalidArgumentException(TouristId::class . ' instance required');
        }
    }
}
