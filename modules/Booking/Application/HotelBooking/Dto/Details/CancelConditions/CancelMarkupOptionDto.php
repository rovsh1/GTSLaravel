<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\Dto\Details\CancelConditions;

use Module\Booking\Domain\Shared\ValueObject\CancelCondition\CancelMarkupOption;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

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
