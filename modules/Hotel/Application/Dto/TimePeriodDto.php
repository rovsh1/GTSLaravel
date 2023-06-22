<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto;

use Module\Hotel\Domain\ValueObject\TimeSettings\BreakfastPeriod;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\TimePeriod;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class TimePeriodDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $from,
        public readonly ?string $to,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|TimePeriod|BreakfastPeriod $entity): static
    {
        return new static(
            $entity->from(),
            $entity->to(),
        );
    }
}
