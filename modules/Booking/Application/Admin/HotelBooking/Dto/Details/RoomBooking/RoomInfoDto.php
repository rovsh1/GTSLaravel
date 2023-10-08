<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking;

use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

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
