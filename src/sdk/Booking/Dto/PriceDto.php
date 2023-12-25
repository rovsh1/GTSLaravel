<?php

declare(strict_types=1);

namespace Sdk\Booking\Dto;

use Sdk\Shared\Enum\CurrencyEnum;

final class PriceDto
{
    public function __construct(
        public readonly CurrencyEnum $currency,
        public readonly float $calculatedValue,
        public readonly ?float $manualValue,
        public readonly ?float $penaltyValue,
    ) {}

    public function toArray(): array
    {
        return [
            'currency' => $this->currency->value,
            'calculatedValue' => $this->calculatedValue,
            'manualValue' => $this->manualValue,
            'penaltyValue' => $this->penaltyValue,
        ];
    }
}