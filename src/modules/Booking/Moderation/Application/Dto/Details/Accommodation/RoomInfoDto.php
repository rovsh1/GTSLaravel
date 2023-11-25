<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details\Accommodation;

use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Booking\ValueObject\HotelBooking\RoomInfo;

class RoomInfoDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $guestsCount,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|RoomInfo $entity): static
    {
        return new static(
            $entity->id(),
            $entity->name(),
            $entity->guestsCount(),
        );
    }
}
