<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\ValueObject\CarBid;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\Enum\CurrencyEnum;

class CarBidPriceItem implements SerializableDataInterface, CanEquate
{
    public function __construct(
        private readonly CurrencyEnum $currency,
        public readonly float $pricePerCar,
        public readonly float $totalAmount,
    ) {}

    public static function createEmpty(CurrencyEnum $currency): static
    {
        return new static($currency, 0, 0);
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function totalAmount(): float
    {
        return $this->totalAmount;
    }

    public function pricePerCar(): float
    {
        return $this->pricePerCar;
    }

    public function toData(): array
    {
        return [
            'totalAmount' => $this->totalAmount,
            'pricePerCar' => $this->pricePerCar,
            'currency' => $this->currency->value,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            CurrencyEnum::from($data['currency']),
            $data['pricePerCar'],
            $data['totalAmount'],
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

        return $this->calculatedValue === $b->calculatedValue
            && $this->currency === $b->currency;
    }
}
