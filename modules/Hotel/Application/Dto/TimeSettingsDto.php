<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto;

use Module\Hotel\Domain\ValueObject\TimeSettings;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class TimeSettingsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $checkInAfter,
        public readonly string $checkOutBefore,
        public readonly ?TimePeriodDto $breakfastPeriod
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|TimeSettings $entity): static
    {
        return new static(
            $entity->checkInAfter()->value(),
            $entity->checkOutBefore()->value(),
            $entity->breakfastPeriod() !== null ? TimePeriodDto::fromDomain($entity->breakfastPeriod()) : null
        );
    }
}
