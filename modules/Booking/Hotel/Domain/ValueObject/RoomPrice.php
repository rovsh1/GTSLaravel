<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class RoomPrice implements SerializableDataInterface
{
    public static function buildEmpty(): RoomPrice
    {
        return new RoomPrice(
            null,
            null,
            new RoomDayPriceCollection()
        );
    }

    public function __construct(
        private readonly ?float $boDayValue,
        private readonly ?float $hoDayValue,
        private readonly RoomDayPriceCollection $dayPrices,
    ) {
    }

    public function isBoManuallyChanged(): bool
    {
        return (bool)$this->boDayValue;
    }

    public function isHoManuallyChanged(): bool
    {
        return (bool)$this->hoDayValue;
    }

    public function boDayValue(): ?float
    {
        return $this->boDayValue;
    }

    public function hoDayValue(): ?float
    {
        return $this->hoDayValue;
    }

    public function dayPrices(): RoomDayPriceCollection
    {
        return $this->dayPrices;
    }

    public function netValue(): float
    {
        return $this->calculateValueSum('netValue');
    }

    public function hoValue(): float
    {
        return $this->calculateValueSum('hoValue');
    }

    public function boValue(): float
    {
        return $this->calculateValueSum('boValue');
    }

    public function toData(): array
    {
        return [
            'boDayValue' => $this->boDayValue,
            'hoDayValue' => $this->hoDayValue,
            'dayPrices' => $this->dayPrices->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomPrice(
            boDayValue: $data['boDayValue'],
            hoDayValue: $data['hoDayValue'],
            dayPrices: RoomDayPriceCollection::fromData($data['dayPrices']),
        );
    }

    private function calculateValueSum(string $key): float
    {
        $netValue = 0.0;
        foreach ($this->dayPrices as $dayPrice) {
            $netValue += $dayPrice->$key();
        }

        return $netValue;
    }
}
