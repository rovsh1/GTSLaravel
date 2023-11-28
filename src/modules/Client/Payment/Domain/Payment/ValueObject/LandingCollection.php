<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Payment\ValueObject;

use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, Landing>
 */
class LandingCollection extends AbstractValueObjectCollection
{
    protected function validateItem(mixed $item): void
    {
        if (!$item instanceof Landing) {
            throw new \InvalidArgumentException();
        }
    }

    public function has(OrderId $orderId): bool
    {
        /** @var Landing $landing */
        foreach ($this->items as $landing) {
            if ($landing->orderId()->isEqual($orderId)) {
                return true;
            }
        }

        return false;
    }

    public function sum(): float
    {
        return array_sum($this->map(fn(Landing $landing) => $landing->sum()));
    }
}
