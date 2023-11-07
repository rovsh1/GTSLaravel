<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details\CancelConditions;

use Module\Booking\Domain\Shared\ValueObject\CancelCondition\CancelMarkupOption;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class CancelMarkupOptionDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $percent,
        public readonly int $cancelPeriodType
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|CancelMarkupOption $entity): static
    {
        return new static(
            $entity->percent()->value(),
            $entity->cancelPeriodType()->value
        );
    }
}
