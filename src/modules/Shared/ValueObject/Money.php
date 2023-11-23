<?php

declare(strict_types=1);

namespace Module\Shared\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Enum\CurrencyEnum;

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

        $valueDiff = abs($b->value - $this->value);

        return $valueDiff < 0.001
            && $this->currency === $b->currency;
    }
}
