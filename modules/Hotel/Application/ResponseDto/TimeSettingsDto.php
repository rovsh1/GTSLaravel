<?php

declare(strict_types=1);

namespace Module\Hotel\Application\ResponseDto;

use Module\Hotel\Domain\ValueObject\TimeSettings;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class TimeSettingsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $checkInAfter,
        public readonly string $checkOutBefore,
        public readonly ?string $breakfastFrom,
        public readonly ?string $breakfastTo,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|TimeSettings $entity): static
    {
        return new static(
            $entity->checkInAfter()->value(),
            $entity->checkOutBefore()->value(),
            $entity->breakfastPeriod()?->from(),
            $entity->breakfastPeriod()?->to(),
        );
    }
}
