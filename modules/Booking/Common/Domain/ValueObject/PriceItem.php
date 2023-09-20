<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Enum\CurrencyEnum;

final class PriceItem implements SerializableDataInterface, CanEquate
{
    public function __construct(
        private readonly CurrencyEnum $currency,
        private readonly float $calculatedValue,
        private readonly ?float $manualValue,
        private readonly ?float $penaltyValue,
    ) {
    }

    public static function createEmpty(CurrencyEnum $currency): PriceItem
    {
        return new PriceItem($currency, 0, null, null);
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
        return new PriceItem(
            CurrencyEnum::from($data['currency']),
            $data['calculatedValue'],
            $data['manualValue'],
            $data['penaltyValue'],
        );
    }

    /**
     * @param PriceItem $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        assert($b instanceof PriceItem);

        return $this->calculatedValue === $b->calculatedValue
            && $this->manualValue === $b->manualValue
            && $this->penaltyValue === $b->penaltyValue
            && $this->currency === $b->currency;
    }
}