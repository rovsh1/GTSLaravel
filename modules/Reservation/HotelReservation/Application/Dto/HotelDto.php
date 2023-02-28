<?php

namespace Module\Reservation\HotelReservation\Application\Dto;

use Module\Reservation\HotelReservation\Domain\ValueObject\Hotel;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class HotelDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int     $id,
        public readonly ?string $checkInTime,
        public readonly ?string $checkOutTime,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Hotel $hotel): static
    {
        return new self(
            $hotel->id(),
            $hotel->checkInTime(),
            $hotel->checkOutTime(),
        );
    }
}
