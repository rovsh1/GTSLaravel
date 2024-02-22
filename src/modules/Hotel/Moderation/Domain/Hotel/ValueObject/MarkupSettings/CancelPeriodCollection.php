<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings;

use Illuminate\Support\Collection;
use Sdk\Shared\Contracts\Support\SerializableInterface;

/**
 * @extends Collection<int, CancelPeriod>
 */
final class CancelPeriodCollection extends Collection implements SerializableInterface
{
    public function serialize(): array
    {
        return $this->map(fn(CancelPeriod $cancelPeriod) => $cancelPeriod->serialize())->values()->all();
    }

    public static function deserialize(array $payload): static
    {
        return (new static($payload))->map(fn(array $item) => CancelPeriod::deserialize($item));
    }
}
