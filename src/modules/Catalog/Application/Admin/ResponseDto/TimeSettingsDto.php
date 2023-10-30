<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\ResponseDto;

use Module\Catalog\Domain\Hotel\ValueObject\TimeSettings;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

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
