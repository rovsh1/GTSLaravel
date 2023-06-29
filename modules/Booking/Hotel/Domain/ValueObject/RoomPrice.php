<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Booking\Common\Domain\ValueObject\PriceCalculationNotes;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class RoomPrice implements SerializableDataInterface
{
    public function __construct(
        private readonly float $netValue,
        private readonly ManualChangablePrice $hoPrice,
        private readonly ManualChangablePrice $boPrice,
        private readonly ?PriceCalculationNotes $calculationNotes,
    ) {}

    public static function buildEmpty(): static
    {
        return new static(0, 0, 0, null);
    }

    public function netValue(): float
    {
        return $this->netValue;
    }

    public function hoValue(): ManualChangablePrice
    {
        return $this->hoPrice;
    }

    public function boValue(): ManualChangablePrice
    {
        return $this->boPrice;
    }

    public function calculationNotes(): ?PriceCalculationNotes
    {
        return $this->calculationNotes;
    }

    public function toData(): array
    {
        return [
            'netValue' => $this->netValue,
            'hoValue' => $this->hoPrice,
            'boValue' => $this->boPrice,
            'calculationNotes' => $this->calculationNotes?->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        $calculationNotes = $data['calculationNotes'] ?? null;
        return new RoomPrice(
            $data['netValue'],
            $data['hoValue'],
            $data['boValue'],
            $calculationNotes !== null ? PriceCalculationNotes::fromData($calculationNotes) : null,
        );
    }
}
