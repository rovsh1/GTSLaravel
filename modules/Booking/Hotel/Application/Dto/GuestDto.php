<?php

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Hotel\Domain\ValueObject\Details\Guest;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class GuestDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $fullName,
        public readonly int $nationalityId,
        public readonly int $gender,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Guest $entity): static
    {
        return new static(
            $entity->fullName(),
            $entity->nationalityId(),
            $entity->gender()->value,
        );
    }
}
