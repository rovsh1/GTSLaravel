<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto\Details\CancelConditions;

use Module\Booking\Domain\Shared\ValueObject\CancelCondition\DailyMarkupOption;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class DailyMarkupDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $percent,
        public readonly int $cancelPeriodType,
        public readonly int $daysCount
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|DailyMarkupOption $entity): static
    {
        return new static(
            $entity->percent()->value(),
            $entity->cancelPeriodType()->value,
            $entity->daysCount()
        );
    }
}
