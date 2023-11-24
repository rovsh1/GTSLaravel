<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\ValueObject;

use Illuminate\Support\Collection;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableInterface;

/**
 * @extends Collection<int, DailyMarkupOption>
 */
final class DailyMarkupCollection extends Collection implements ValueObjectInterface, SerializableInterface
{
    public function serialize(): array
    {
        return $this->map(fn(DailyMarkupOption $dailyMarkupPercent) => $dailyMarkupPercent->serialize())->values()->all();
    }

    public static function deserialize(array $payload): static
    {
        return (new static($payload))->map(fn(array $item) => DailyMarkupOption::deserialize($item));
    }
}
