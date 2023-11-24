<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings;

use Illuminate\Support\Collection;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableInterface;

/**
 * @extends Collection<int, Condition>
 */
final class LateCheckOutCollection extends Collection implements ValueObjectInterface, SerializableInterface
{
    public function serialize(): array
    {
        return $this->map(fn(Condition $condition) => $condition->serialize())->values()->all();
    }

    public static function deserialize(array $payload): static
    {
        return (new static($payload))->map(fn(array $item) => Condition::deserialize($item));
    }
}
