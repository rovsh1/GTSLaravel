<?php

namespace Module\Booking\Moderation\Application\Dto;

use Module\Booking\Domain\Guest\Guest;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class GuestDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $orderId,
        public readonly string $fullName,
        public readonly int $countryId,
        public readonly int $gender,
        public readonly bool $isAdult,
        public readonly ?int $age
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Guest $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->orderId()->value(),
            $entity->fullName(),
            $entity->countryId(),
            $entity->gender()->value,
            $entity->isAdult(),
            $entity->age()
        );
    }
}
