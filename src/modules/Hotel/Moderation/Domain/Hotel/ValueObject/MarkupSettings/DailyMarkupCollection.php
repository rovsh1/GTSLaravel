<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings;

use Illuminate\Support\Collection;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;

/**
 * @extends Collection<int, DailyMarkupOption>
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