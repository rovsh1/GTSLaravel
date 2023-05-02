<?php

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Hotel\Domain\ValueObject\Hotel;
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

    public static function fromDomain(EntityInterface|ValueObjectInterface|Hotel $hotel): self
    {
        return new self(
            $hotel->id(),
            $hotel->checkInTime(),
            $hotel->checkOutTime(),
        );
    }
}
