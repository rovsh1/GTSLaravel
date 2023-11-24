<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject\CarBid;

use Module\Shared\Contracts\Support\CanEquate;
use Module\Shared\Contracts\Support\SerializableInterface;
use Module\Shared\Enum\CurrencyEnum;

class CarBidPriceItem implements SerializableInterface, CanEquate
{
    public function __construct(
        private readonly CurrencyEnum $currency,
        private readonly float $valuePerCar,
    ) {}

    public static function createEmpty(CurrencyEnum $currency): static
    {
        return new static($currency, 0);
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function valuePerCar(): float
    {
        return $this->valuePerCar;
    }

    public function serialize(): array
    {
        return [
            'valuePerCar' => $this->valuePerCar,
            'currency' => $this->currency->value,
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            CurrencyEnum::from($payload['currency']),
            $payload['valuePerCar'],
        );
    }

    /**
     * @param CarBidPriceItem $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        if (!$b instanceof CarBidPriceItem) {
            return $this === $b;
        }

        return $this->valuePerCar === $b->valuePerCar
            && $this->currency === $b->currency;
    }
}
