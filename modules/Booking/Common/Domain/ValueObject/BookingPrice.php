<?php

namespace Module\Booking\Common\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class BookingPrice implements SerializableDataInterface
{
    public function __construct(
        private readonly float $netValue,
        private readonly float $hoValue,
        private readonly float $boValue,
        private readonly bool $isManual = false,
    ) {}

    public static function buildEmpty(): static
    {
        return new static(0, 0, 0);
    }

    public function netValue(): float
    {
        return $this->netValue;
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
            'hoValue' => $this->hoValue,
            'boValue' => $this->boValue,
            'isManual' => $this->isManual
        ];
    }

    public static function fromData(array $data): static
    {
        return new BookingPrice(
            $data['netValue'],
            $data['hoValue'],
            $data['boValue'],
            $data['isManual'],
        );
    }
}
