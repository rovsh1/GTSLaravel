<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Payment\ValueObject;

use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Module\Support\AbstractValueObjectCollection;
use Sdk\Shared\Contracts\Support\CanEquate;

/**
 * @extends AbstractValueObjectCollection<int, Landing>
 */
class LandingCollection extends AbstractValueObjectCollection implements CanEquate
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

    public function isEqual(mixed $b): bool
    {
        if (!$b instanceof LandingCollection) {
            return $b === $this;
        }

        if ($this->count() !== $b->count()) {
            return false;
        }

        /** @var array<string, int> $counts */
        $counts = collect($this->items)->reduce(function (array $result, Landing $item) {
            $key = $this->getCompareKey($item);
            $result[$key] = ($result[$key] ?? 0) + 1;
            return $result;
        }, []);

        foreach ($b->items as $item) {
            $key = $this->getCompareKey($item);
            if (!array_key_exists($key, $counts) || $counts[$key] === 0) {
                return false;
            }
            $counts[$key]--;
        }

        return true;
    }

    private function getCompareKey(Landing $landing): string
    {
        return $landing->orderId()->value() . '_' . (int)$landing->sum();
    }
}
