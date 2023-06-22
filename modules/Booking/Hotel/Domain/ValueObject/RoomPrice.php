<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class RoomPrice implements SerializableDataInterface
{
    public function __construct(
        private readonly float $netValue,
        private readonly float $avgDailyValue,
        private readonly float $hoValue,
        private readonly float $boValue
    ) {}

    public static function buildEmpty(): static
    {
        return new static(0, 0, 0, 0);
    }

    public function netValue(): float
    {
        return $this->netValue;
    }

    public function avgDailyValue(): float
    {
        return $this->avgDailyValue;
    }

    public function hoValue(): float
    {
        return $this->hoValue;
    }

    public function boValue(): float
    {
        return $this->boValue;
    }

    public function toData(): array
    {
        return [
            'netValue' => $this->netValue,
            'avgDailyValue' => $this->avgDailyValue,
            'hoValue' => $this->hoValue,
            'boValue' => $this->boValue,
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomPrice(
            $data['netValue'],
            $data['avgDailyValue'],
            $data['hoValue'],
            $data['boValue'],
        );
    }
}
