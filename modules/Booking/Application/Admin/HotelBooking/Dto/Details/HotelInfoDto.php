<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto\Details;

use Module\Booking\Domain\HotelBooking\ValueObject\Details\HotelInfo;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

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
