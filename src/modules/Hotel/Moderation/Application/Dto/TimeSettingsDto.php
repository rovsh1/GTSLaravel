<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Dto;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\TimeSettings;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class TimeSettingsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $checkInAfter,
        public readonly string $checkOutBefore,
        public readonly ?string $breakfastFrom,
        public readonly ?string $breakfastTo,
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof TimeSettings);

        return new static(
            $entity->checkInAfter()->value(),
            $entity->checkOutBefore()->value(),
            $entity->breakfastPeriod()?->from(),
            $entity->breakfastPeriod()?->to(),
        );
    }
}
