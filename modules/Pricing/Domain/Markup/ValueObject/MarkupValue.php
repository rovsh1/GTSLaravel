<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Module\Shared\Enum\Pricing\MarkupValueTypeEnum;

final class MarkupValue implements ValueObjectInterface, CanEquate
{
    public function __construct(
        private readonly int $value,
        private readonly MarkupValueTypeEnum $type
    ) {
    }

    public static function createZero(): MarkupValue
    {
        return new MarkupValue(0, MarkupValueTypeEnum::ABSOLUTE);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function type(): MarkupValueTypeEnum
    {
        return $this->type;
    }

    public function calculate(int|float $price): float|int
    {
        switch ($this->type) {
            case MarkupValueTypeEnum::ABSOLUTE:
                return $this->value;
            case MarkupValueTypeEnum::PERCENT:
                return $price * $this->value / 100;
        }
    }

    public function isEqual(mixed $b): bool
    {
        return $b instanceof MarkupValue
            ? $this->value === $b->value && $this->type === $b->type
            : $this === $b;
    }
}
