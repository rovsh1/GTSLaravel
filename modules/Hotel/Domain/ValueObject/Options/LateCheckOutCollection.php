<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Illuminate\Support\Collection;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

/**
 * @extends Collection<int, Condition>
 */
class LateCheckOutCollection extends Collection implements SerializableDataInterface
{
    public function toData(): array
    {
        return $this->map(fn(Condition $condition) => $condition->toData())->all();
    }

    public static function fromData(array $data): static
    {
        return (new static($data))->map(fn(array $item) => Condition::fromData($item));
    }
}
