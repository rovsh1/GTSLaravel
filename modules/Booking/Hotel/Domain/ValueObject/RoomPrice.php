<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class RoomPrice implements SerializableDataInterface
{
    public function __construct(
        private readonly float $netValue,
        private readonly float $avgDailyValue,
        private readonly float $hotelValue,
        private readonly float $clientValue
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

    public function hotelValue(): float
    {
        return $this->hotelValue;
    }

    public function clientValue(): float
    {
        return $this->clientValue;
    }

    public function toData(): array
    {
        return [
            'netValue' => $this->netValue,
            'avgDailyValue' => $this->avgDailyValue,
            'hotelValue' => $this->hotelValue,
            'clientValue' => $this->clientValue,
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomPrice(
            $data['netValue'],
            $data['avgDailyValue'],
            $data['hotelValue'],
            $data['clientValue'],
        );
    }
}
