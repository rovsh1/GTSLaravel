<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details\CancelConditions;

use Sdk\Booking\ValueObject\CancelCondition\CancelFeeValue;
use Sdk\Shared\Enum\Pricing\ValueTypeEnum;

class CancelFeeValueDto
{
    public function __construct(
        public readonly float $value,
        public readonly ValueTypeEnum $valueType,
        public readonly int $cancelPeriodType
    ) {}

    public static function fromDomain(CancelFeeValue $entity): static
    {
        return new static(
            $entity->value()->value(),
            $entity->value()->type(),
            $entity->cancelPeriodType()->value
        );
    }
}
