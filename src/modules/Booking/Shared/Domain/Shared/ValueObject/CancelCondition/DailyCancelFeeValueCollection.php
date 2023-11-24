<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition;

use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, DailyCancelFeeValue>
 */
final class DailyCancelFeeValueCollection extends AbstractValueObjectCollection
{
    public function toData(): array
    {
        return $this->map(fn(DailyCancelFeeValue $dailyMarkupPercent) => $dailyMarkupPercent->serialize());
    }

    public static function fromData(array $data): static
    {
        $items = array_map(fn(array $item) => DailyCancelFeeValue::deserialize($item), $data);

        return (new static($items));
    }

    public function add(DailyCancelFeeValue $markupOption): void
    {
        $this->items = [...$this->items, $markupOption];
    }
}
