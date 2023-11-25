<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Payment\ValueObject;

use Sdk\Shared\Enum\CurrencyEnum;

final class PaymentAmount
{
    private readonly float $sum;

    public function __construct(
        private readonly CurrencyEnum $currency,
        float $sum,
        private readonly int $methodId,
    ) {
        $this->validateSum($sum);
        $this->sum = $sum;
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function sum(): float
    {
        return $this->sum;
    }

    public function methodId(): int
    {
        return $this->methodId;
    }

    private function validateSum(float $value): void
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException();
        }
    }
}
