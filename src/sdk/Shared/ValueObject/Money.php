<?php

declare(strict_types=1);

namespace Sdk\Shared\ValueObject;

use Sdk\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Enum\CurrencyEnum;

final class Money implements CanEquate
{
    public function __construct(
        private readonly CurrencyEnum $currency,
        private readonly float $value,
    ) {}

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function value(): float
    {
        return $this->value;
    }

    /**
     * @param Money $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        if (!$b instanceof Money) {
            return $this === $b;
        }

        return $this->value === $b->value
            && $this->currency === $b->currency;
    }
}