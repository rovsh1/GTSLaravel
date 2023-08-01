<?php

namespace Module\Booking\Common\Domain\ValueObject;

use Module\Booking\HotelBooking\Domain\ValueObject\ManualChangablePrice;
use Module\Shared\Contracts\CanEquate;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class BookingPrice implements SerializableDataInterface, CanEquate
{
    public function __construct(
        private readonly float $netValue,
        private readonly ManualChangablePrice $hoPrice,
        private readonly ManualChangablePrice $boPrice,
        private readonly ?float $hoPenalty,
        private readonly ?float $boPenalty,
    ) {}

    public static function buildEmpty(): static
    {
        return new static(
            0,
            new ManualChangablePrice(0),
            new ManualChangablePrice(0),
            null,
            null,
        );
    }

    public function netValue(): float
    {
        return $this->netValue;
    }

    public function hoPrice(): ManualChangablePrice
    {
        return $this->hoPrice;
    }

    public function boPrice(): ManualChangablePrice
    {
        return $this->boPrice;
    }

    public function hoPenalty(): ?float
    {
        return $this->hoPenalty;
    }

    public function boPenalty(): ?float
    {
        return $this->boPenalty;
    }

    public function toData(): array
    {
        return [
            'netValue' => $this->netValue,
            'hoPrice' => $this->hoPrice->toData(),
            'boPrice' => $this->boPrice->toData(),
            'hoPenalty' => $this->hoPenalty,
            'boPenalty' => $this->boPenalty,
        ];
    }

    public static function fromData(array $data): static
    {
        return new BookingPrice(
            $data['netValue'],
            ManualChangablePrice::fromData($data['hoPrice']),
            ManualChangablePrice::fromData($data['boPrice']),
            $data['hoPenalty'],
            $data['boPenalty'],
        );
    }

    /**
     * @param static $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        return $this->netValue === $b->netValue
            && $this->boPrice->isEqual($b->boPrice)
            && $this->hoPrice->isEqual($b->hoPrice)
            && $this->hoPenalty === $b->hoPenalty
            && $this->boPenalty === $b->boPenalty;
    }
}
