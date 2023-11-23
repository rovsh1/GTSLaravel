<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\Enum\CurrencyEnum;

final class OrderPrice implements CanEquate
{
    public function __construct(
        private readonly CurrencyEnum $currency,
        private readonly float $value,
    ) {
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
     * @param OrderPrice $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        assert($b instanceof OrderPrice);

        return $this->value === $b->value
            && $this->currency === $b->currency;
    }
}
