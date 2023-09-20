<?php

declare(strict_types=1);

namespace Module\Client\Domain\Payment\ValueObject;

final class InvoiceNumber
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $this->validateValue($value);
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    private function validateValue(string $value): void
    {
        if (empty($value)) {
            throw new \InvalidArgumentException();
        }
    }
}