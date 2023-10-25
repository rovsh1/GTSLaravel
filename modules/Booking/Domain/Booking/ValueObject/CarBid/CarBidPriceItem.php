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
        private readonly float $calculatedValue,
    ) {}

    public static function createEmpty(CurrencyEnum $currency): static
    {
        return new static($currency, 0);
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function calculatedValue(): float
    {
        return $this->calculatedValue;
    }

    public function toData(): array
    {
        return [
            'calculatedValue' => $this->calculatedValue,
            'currency' => $this->currency->value,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            CurrencyEnum::from($data['currency']),
            $data['calculatedValue'],
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
