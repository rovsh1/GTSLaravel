<?php

declare(strict_types=1);

namespace Sdk\Shared\ValueObject;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Enum\Pricing\ValueTypeEnum;

final class MarkupValue implements ValueObjectInterface, CanEquate
{
    public function __construct(
        private readonly int $value,
        private readonly ValueTypeEnum $type
    ) {
    }

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

    public function calculate(int|float $price): float|int
    {
        switch ($this->type) {
            case ValueTypeEnum::ABSOLUTE:
                return $this->value;
            case ValueTypeEnum::PERCENT:
                return $price * $this->value / 100;
        }
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
