<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Booking\Common\Domain\ValueObject\PriceCalculationNotes;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class RoomPrice implements SerializableDataInterface
{
    public function __construct(
        private readonly float $netValue,
        private readonly float $avgDailyValue,
        private readonly float $hoValue,
        private readonly float $boValue,
        private readonly ?PriceCalculationNotes $calculationNotes,
    ) {}

    public static function buildEmpty(): static
    {
        return new static(0, 0, 0, 0, null);
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

    public function calculationNotes(): ?PriceCalculationNotes
    {
        return $this->calculationNotes;
    }

    public function toData(): array
    {
        return [
            'netValue' => $this->netValue,
            'avgDailyValue' => $this->avgDailyValue,
            'hoValue' => $this->hoValue,
            'boValue' => $this->boValue,
            'calculationNotes' => $this->calculationNotes?->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        $calculationNotes = $data['calculationNotes'] ?? null;
        return new RoomPrice(
            $data['netValue'],
            $data['avgDailyValue'],
            $data['hoValue'],
            $data['boValue'],
            $calculationNotes !== null ? PriceCalculationNotes::fromData($calculationNotes) : null,
        );
    }
}
