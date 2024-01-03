<?php

namespace Services\CurrencyRate\ValueObject;

use Exception;
use Sdk\Shared\Enum\CurrencyEnum;

class CurrencyRate
{
    private readonly float $value;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly CurrencyEnum $currency,
        float $value,
        private readonly int $nominal
    ) {
        $this->value = $this->validateValue($value);
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function rate(): float
    {
        return $this->value / $this->nominal;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function nominal(): int
    {
        return $this->nominal;
    }

    /**
     * @throws Exception
     */
    private function validateValue(float $rate): float
    {
        if ($rate <= 0) {
            throw new Exception();
        }
        return $rate;
    }
}