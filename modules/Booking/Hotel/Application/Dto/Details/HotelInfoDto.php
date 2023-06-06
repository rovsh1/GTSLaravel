<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto\Details;

use Module\Booking\Hotel\Domain\ValueObject\Details\HotelInfo;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class HotelInfoDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $checkInTime,
        public readonly string $checkOutTime,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|HotelInfo $entity): static
    {
        return new static(
            $entity->id(),
            $entity->name(),
            $entity->checkInTime()->value(),
            $entity->checkOutTime()->value(),
        );
    }
}
