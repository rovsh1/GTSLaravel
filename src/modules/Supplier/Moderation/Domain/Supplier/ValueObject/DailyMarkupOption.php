<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\ValueObject;

use Sdk\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\Percent;

final class DailyMarkupOption implements SerializableInterface, CanEquate
{
    public function __construct(
        private readonly Percent $percent,
        private readonly int $daysCount
    ) {}

    public function percent(): Percent
    {
        return $this->percent;
    }

    public function daysCount(): int
    {
        return $this->daysCount;
    }

    public function serialize(): array
    {
        return [
            'percent' => $this->percent->value(),
            'daysCount' => $this->daysCount,
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            new Percent($payload['percent']),
            $payload['daysCount'],
        );
    }

    public function isEqual(mixed $b): bool
    {
        return $b instanceof DailyMarkupOption
            ? $this->percent->isEqual($b->percent) && $this->daysCount === $b->daysCount
            : $this === $b;
    }
}
