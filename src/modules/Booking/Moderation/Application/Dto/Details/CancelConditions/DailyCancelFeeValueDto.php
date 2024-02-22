<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details\CancelConditions;

use Sdk\Booking\ValueObject\CancelCondition\DailyCancelFeeValue;
use Sdk\Shared\Enum\Pricing\ValueTypeEnum;

class DailyCancelFeeValueDto
{
    public function __construct(
        public readonly float $value,
        public readonly ValueTypeEnum $valueType,
        public readonly int $cancelPeriodType,
        public readonly int $daysCount
    ) {}

    public static function fromDomain(DailyCancelFeeValue $entity): static
    {
        return new static(
            $entity->value()->value(),
            $entity->value()->type(),
            $entity->cancelPeriodType()->value,
            $entity->daysCount()
        );
    }

    public static function collectionFromDomain(array $items): array
    {
        return array_map(fn(DailyCancelFeeValue $feeValue) => static::fromDomain($feeValue), $items);
    }
}
