<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Hotel\Domain\Entity\Details\Hotel;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class HotelDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $checkInTime,
        public readonly string $checkOutTime,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Hotel $entity): static
    {
        return new static(
            $entity->id(),
            $entity->checkInTime()->value(),
            $entity->checkOutTime()->value(),
        );
    }
}
