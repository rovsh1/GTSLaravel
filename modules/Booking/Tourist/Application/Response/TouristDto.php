<?php

namespace Module\Booking\Tourist\Application\Response;

use Module\Booking\Tourist\Domain\Tourist;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class TouristDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $fullName,
        public readonly int $countryId,
        public readonly int $gender,
        public readonly bool $isAdult,
        public readonly ?int $age
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Tourist $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->fullName(),
            $entity->countryId(),
            $entity->gender()->value,
            $entity->isAdult(),
            $entity->age()
        );
    }
}
