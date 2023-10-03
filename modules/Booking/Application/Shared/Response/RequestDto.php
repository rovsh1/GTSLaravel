<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Response;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Shared\Entity\Request;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RequestDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $type,
        public readonly CarbonImmutable $dateCreate
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Request $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->type()->value,
            $entity->dateCreate()
        );
    }
}
