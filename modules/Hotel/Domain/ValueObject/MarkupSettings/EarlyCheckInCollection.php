<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\MarkupSettings;

use Illuminate\Support\Collection;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

/**
 * @extends Collection<int, Condition>
 */
class EarlyCheckInCollection extends Collection implements ValueObjectInterface, SerializableDataInterface
{
    public function toData(): array
    {
        return $this->map(fn(Condition $condition) => $condition->toData())->values()->all();
    }

    public static function fromData(array $data): static
    {
        return (new static($data))->map(fn(array $item) => Condition::fromData($item));
    }
}
