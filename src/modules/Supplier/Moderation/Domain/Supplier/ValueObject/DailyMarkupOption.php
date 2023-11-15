<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\ValueObject\Percent;

final class DailyMarkupOption implements ValueObjectInterface, SerializableDataInterface, CanEquate
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

    public function toData(): array
    {
        return [
            'percent' => $this->percent->value(),
            'daysCount' => $this->daysCount,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            new Percent($data['percent']),
            $data['daysCount'],
        );
    }

    public function isEqual(mixed $b): bool
    {
        return $b instanceof DailyMarkupOption
            ? $this->percent->isEqual($b->percent) && $this->daysCount === $b->daysCount
            : $this === $b;
    }
}
