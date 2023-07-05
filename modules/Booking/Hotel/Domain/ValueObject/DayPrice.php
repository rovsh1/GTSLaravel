<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject;

use Carbon\CarbonImmutable;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class DayPrice implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly CarbonImmutable $date,
        private readonly int|float $value
    ) {}

    public function date(): CarbonImmutable
    {
        return $this->date;
    }

    public function value(): float|int
    {
        return $this->value;
    }

    public function toData(): array
    {
        return [
            'date' => $this->date->getTimestamp(),
            'value' => $this->value,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            CarbonImmutable::createFromTimestamp($data['date']),
            $data['value']
        );
    }
}
