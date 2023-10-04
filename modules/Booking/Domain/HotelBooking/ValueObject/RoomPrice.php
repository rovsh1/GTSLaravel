<?php

namespace Module\Booking\Domain\HotelBooking\ValueObject;

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
        private readonly ?float $grossDayValue,
        private readonly ?float $netDayValue,
        private readonly RoomDayPriceCollection $dayPrices,
    ) {
    }

    public function isGrossManuallyChanged(): bool
    {
        return (bool)$this->grossDayValue;
    }

    public function isNetManuallyChanged(): bool
    {
        return (bool)$this->netDayValue;
    }

    public function grossDayValue(): ?float
    {
        return $this->grossDayValue;
    }

    public function netDayValue(): ?float
    {
        return $this->netDayValue;
    }

    public function dayPrices(): RoomDayPriceCollection
    {
        return $this->dayPrices;
    }

    public function baseValue(): float
    {
        return $this->calculateValueSum('baseValue');
    }

    public function netValue(): float
    {
        return $this->calculateValueSum('netValue');
    }

    public function grossValue(): float
    {
        return $this->calculateValueSum('grossValue');
    }

    public function toData(): array
    {
        return [
            'grossDayValue' => $this->grossDayValue,
            'netDayValue' => $this->netDayValue,
            'dayPrices' => $this->dayPrices->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomPrice(
            grossDayValue: $data['grossDayValue'],
            netDayValue: $data['netDayValue'],
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
