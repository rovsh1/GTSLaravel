<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Illuminate\Support\Collection;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

/**
 * @extends Collection<int, DailyMarkupOption>
 */
class DailyMarkupCollection extends Collection implements SerializableDataInterface
{
    public function toData(): array
    {
        return $this->map(fn(DailyMarkupOption $dailyMarkupPercent) => $dailyMarkupPercent->toData())->all();
    }

    public static function fromData(array $data): static
    {
        return (new static($data))->map(fn(array $item) => DailyMarkupOption::fromData($item));
    }
}
