<?php

namespace Module\Booking\Application\Order\Response;

use Module\Booking\Domain\Order\Entity\Guest;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

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