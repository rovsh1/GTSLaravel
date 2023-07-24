<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking;

use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomInfoDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|RoomInfo $entity): static
    {
        return new static(
            $entity->id(),
            $entity->name(),
        );
    }
}
