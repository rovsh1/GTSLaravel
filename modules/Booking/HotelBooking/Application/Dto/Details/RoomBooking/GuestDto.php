<?php

namespace Module\Booking\HotelBooking\Application\Dto\Details\RoomBooking;

use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\Guest;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class GuestDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $fullName,
        public readonly int $countryId,
        public readonly int $gender,
        public readonly bool $isAdult,
        public readonly ?int $age
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Guest $entity): static
    {
        return new static(
            $entity->fullName(),
            $entity->countryId(),
            $entity->gender()->value,
            $entity->isAdult(),
            $entity->age()
        );
    }
}
