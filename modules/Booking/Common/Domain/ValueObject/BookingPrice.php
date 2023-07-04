<?php

namespace Module\Booking\Common\Domain\ValueObject;

use Module\Booking\Hotel\Domain\ValueObject\ManualChangablePrice;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class BookingPrice implements SerializableDataInterface
{
    public function __construct(
        private readonly float $netValue,
        private readonly ManualChangablePrice $hoValue,
        private readonly ManualChangablePrice $boValue,
        private readonly bool $isManual = false,
    ) {}

    public static function buildEmpty(): static
    {
        return new static(
            0,
            new ManualChangablePrice(0),
            new ManualChangablePrice(0),
        );
    }

    public function netValue(): float
    {
        return $this->netValue;
    }

    public function hoValue(): ManualChangablePrice
    {
        return $this->hoValue;
    }

    public function boValue(): ManualChangablePrice
    {
        return $this->boValue;
    }

    public function toData(): array
    {
        return [
            'netValue' => $this->netValue,
            'hoValue' => $this->hoValue->toData(),
            'boValue' => $this->boValue->toData(),
            'isManual' => $this->isManual
        ];
    }

    public static function fromData(array $data): static
    {
        return new BookingPrice(
            $data['netValue'],
            ManualChangablePrice::fromData($data['hoValue']),
            ManualChangablePrice::fromData($data['boValue']),
            $data['isManual'],
        );
    }
}
