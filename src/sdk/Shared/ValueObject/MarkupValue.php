<?php

declare(strict_types=1);

namespace Sdk\Shared\ValueObject;

use Sdk\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Enum\CurrencyEnum;
use Sdk\Shared\Enum\Pricing\ValueTypeEnum;

final class MarkupValue implements CanEquate
{
    public function __construct(
        private readonly int $value,
        private readonly ValueTypeEnum $type
    ) {}

    public static function createZero(): MarkupValue
    {
        return new MarkupValue(0, ValueTypeEnum::ABSOLUTE);
    }

    public static function createPercent(int $percent): MarkupValue
    {
        return new MarkupValue($percent, ValueTypeEnum::PERCENT);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function type(): ValueTypeEnum
    {
        return $this->type;
    }

    public function calculate(int|float $price, CurrencyEnum $currency): float|int
    {
        $value = match ($this->type) {
            ValueTypeEnum::ABSOLUTE => $this->value,
            ValueTypeEnum::PERCENT => $price * $this->value / 100
        };

        return Money::round($currency, $value);
    }

    public function isEqual(mixed $b): bool
    {
        return $b instanceof MarkupValue
            ? $this->value === $b->value && $this->type === $b->type
            : $this === $b;
    }

    public function __toString(): string
    {
        switch ($this->type) {
            case ValueTypeEnum::ABSOLUTE:
                return (string)$this->value;
            case ValueTypeEnum::PERCENT:
                return $this->value . '%';
        }
    }
}
