<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details\CancelConditions;

use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Booking\ValueObject\CancelCondition\DailyCancelFeeValue;

class DailyMarkupDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $percent,
        public readonly int $cancelPeriodType,
        public readonly int $daysCount
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|DailyCancelFeeValue $entity): static
    {
        return new static(
            $entity->value()->value(),
            $entity->cancelPeriodType()->value,
            $entity->daysCount()
        );
    }
}
