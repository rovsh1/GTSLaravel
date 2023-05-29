<?php

namespace Module\Shared\Domain\ValueObject;

use Sdk\Module\Foundation\Exception\ValidationException;

class Price
{
    private ?float $value;

    private ?string $currency;

    public function __construct(?float $value = null, ?string $currency = null)
    {
        if ($value && !$currency) {
            throw new \ArgumentCountError('Price currency required');
        }

        $this->setValue($value);
        $this->setCurrency($currency);
    }

    public function value(): float
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

    public function __toString(): string
    {
        return $this->value ? $this->value . ' ' . $this->currency : '';
    }

    private function validateValue(float $value): void
    {
        if (!$value < 0) {
            throw new ValidationException('Price value validation failed');
        }
    }

    private function validateCurrency(string $currency): void
    {
        if (!preg_match('/^[A-Z]{3}$/', $currency)) {
            throw new ValidationException('Price currency validation failed');
        }
    }
}
