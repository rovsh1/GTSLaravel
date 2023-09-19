<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Invoice\ValueObject;

final class PaymentAmount
{
    private readonly float $sum;

    public function __construct(
        float $sum,
        private readonly PaymentMethodEnum $method
    ) {
        $this->validateSum($sum);
        $this->sum = $sum;
    }

    public function sum(): float
    {
        return $this->sum;
    }

    public function method(): PaymentMethodEnum
    {
        return $this->method;
    }

    private function validateSum(float $value): void
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException();
        }
    }
}
