<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition;

use Illuminate\Support\Collection;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

/**
 * @extends Collection<int, DailyMarkupOption>
 *
 * @see \Module\Hotel\Domain\ValueObject\MarkupSettings\DailyMarkupCollection
 */
final class DailyMarkupCollection extends Collection implements ValueObjectInterface, SerializableDataInterface
{
    public function toData(): array
    {
        return $this->map(fn(DailyMarkupOption $dailyMarkupPercent) => $dailyMarkupPercent->toData())->values()->all();
    }

    public static function fromData(array $data): static
    {
        return (new static($data))->map(fn(array $item) => DailyMarkupOption::fromData($item));
    }
}
