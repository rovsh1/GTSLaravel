<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition;

use Sdk\Module\Support\AbstractValueObjectCollection;

/**
 * @extends AbstractValueObjectCollection<int, DailyMarkupOption>
 *
 * @see \Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\DailyMarkupCollection
 */
final class DailyMarkupCollection extends AbstractValueObjectCollection
{
    public function toData(): array
    {
        return $this->map(fn(DailyMarkupOption $dailyMarkupPercent) => $dailyMarkupPercent->toData());
    }

    public static function fromData(array $data): static
    {
        $items = array_map(fn(array $item) => DailyMarkupOption::fromData($item), $data);

        return (new static($items));
    }

    public function add(DailyMarkupOption $markupOption): void
    {
        $this->items = [...$this->items, $markupOption];
    }
}
