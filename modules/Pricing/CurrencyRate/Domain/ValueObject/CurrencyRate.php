<?php

namespace Module\Pricing\CurrencyRate\Domain\ValueObject;

use Exception;

class CurrencyRate
{
    private float $value;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly CurrencyEnum $currency,
        float $value
    ) {
        $this->setValue($value);
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function value(): float
    {
        return $this->value;
    }

    /**
     * @throws Exception
     */
    public function setValue(float $value): void
    {
        $this->value = $this->validateValue($value);
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