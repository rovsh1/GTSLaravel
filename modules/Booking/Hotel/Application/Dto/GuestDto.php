<?php

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Hotel\Domain\Entity\Room;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class GuestDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $fullName,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Room\Guest $guest): static
    {
        return new static(
            $guest->id(),
            $guest->fullName(),
        );
    }
}
