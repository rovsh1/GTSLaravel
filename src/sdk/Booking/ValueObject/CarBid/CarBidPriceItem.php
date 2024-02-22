<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject\CarBid;

use Sdk\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\Enum\CurrencyEnum;

class CarBidPriceItem implements SerializableInterface, CanEquate
{
    public function __construct(
        private readonly CurrencyEnum $currency,
        private readonly float $valuePerCar,
        private readonly ?float $manualValuePerCar = null,
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

    public function manualValuePerCar(): ?float
    {
        return $this->manualValuePerCar;
    }

    public function serialize(): array
    {
        return [
            'valuePerCar' => $this->valuePerCar,
            'manualValuePerCar' => $this->manualValuePerCar,
            'currency' => $this->currency->value,
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            CurrencyEnum::from($payload['currency']),
            $payload['valuePerCar'],
            $payload['manualValuePerCar'] ?? null,
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
            && $this->currency === $b->currency
            && $this->manualValuePerCar === $b->manualValuePerCar;
    }
}
