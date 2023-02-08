<?php

namespace GTS\Shared\Domain\ValueObject;

use Custom\Framework\Foundation\Exception\ValidationException;

class Price
{
    private ?string $value;

    private ?string $currency;

    public function __construct(?float $value = null, ?string $currency = null)
    {
        if ($value && !$currency)
            throw new \ArgumentCountError('Price currency required');

        $this->setValue($value);
        $this->setCurrency($currency);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function setValue(float $value): void
    {
        //$this->validateValue($value);

        $this->value = $value;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->validateCurrency($currency);

        $this->currency = $currency;
    }

    public function reset(): void
    {
        $this->value = null;
        $this->currency = null;
    }

    private function validateValue(float $value): void
    {
        if (!$value < 0)
            throw new ValidationException('Price value validation failed');
    }

    private function validateCurrency(string $currency): void
    {
        if (!preg_match('/^[A-Z]{3}$/', $currency))
            throw new ValidationException('Price currency validation failed');
    }
}
