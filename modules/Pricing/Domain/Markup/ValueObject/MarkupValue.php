<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Domain\ValueObject\Uint;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Module\Shared\Enum\Pricing\MarkupValueTypeEnum;

class MarkupValue implements ValueObjectInterface, CanEquate
{
    public function __construct(
        private readonly Uint $value,
        private readonly MarkupValueTypeEnum $type
    ) {}

    public function value(): Uint
    {
        return $this->value;
    }

    public function type(): MarkupValueTypeEnum
    {
        return $this->type;
    }

    public function isEqual(mixed $b): bool
    {
        return $b instanceof MarkupValue
            ? $this->value->isEqual($b->value) && $this->type === $b->type
            : $this === $b;
    }
}
