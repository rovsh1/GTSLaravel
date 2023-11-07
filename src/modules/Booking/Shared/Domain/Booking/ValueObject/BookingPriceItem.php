<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\Enum\CurrencyEnum;

final class BookingPriceItem implements SerializableDataInterface, CanEquate
{
    public function __construct(
        private readonly CurrencyEnum $currency,
        private readonly float $calculatedValue,
        private readonly ?float $manualValue,
        private readonly ?float $penaltyValue,
    ) {
    }

    public static function createEmpty(CurrencyEnum $currency): BookingPriceItem
    {
        return new BookingPriceItem($currency, 0, null, null);
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function calculatedValue(): float
    {
        return $this->calculatedValue;
    }

    public function manualValue(): ?float
    {
        return $this->manualValue;
    }

    public function penaltyValue(): ?float
    {
        return $this->penaltyValue;
    }

    public function toData(): array
    {
        return [
            'calculatedValue' => $this->calculatedValue,
            'manualValue' => $this->manualValue,
            'penaltyValue' => $this->penaltyValue,
            'currency' => $this->currency->value,
        ];
    }

    public static function fromData(array $data): static
    {
        return new BookingPriceItem(
            CurrencyEnum::from($data['currency']),
            $data['calculatedValue'],
            $data['manualValue'],
            $data['penaltyValue'],
        );
    }

    /**
     * @param BookingPriceItem $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        assert($b instanceof BookingPriceItem);

        return $this->calculatedValue === $b->calculatedValue
            && $this->manualValue === $b->manualValue
            && $this->penaltyValue === $b->penaltyValue
            && $this->currency === $b->currency;
    }
}
