<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\ValueObject;

use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Support\AbstractValueObjectCollection;

final class OrderIdCollection extends AbstractValueObjectCollection
{
    public function has(OrderId $orderId): bool
    {
        foreach ($this->items as $itemId) {
            if ($orderId->isEqual($itemId)) {
                return true;
            }
        }

        return false;
    }

    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof OrderId) {
            throw new \InvalidArgumentException();
        } elseif ($this->has($item)) {
            throw new \InvalidArgumentException();
        }
    }

    protected function validateItems(iterable $items): void
    {
        if (empty($items)) {
            throw new \InvalidArgumentException();
        }

        parent::validateItems($items);
    }
}
