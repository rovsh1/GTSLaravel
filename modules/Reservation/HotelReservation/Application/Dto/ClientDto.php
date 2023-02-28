<?php

namespace Module\Reservation\HotelReservation\Application\Dto;

use Module\Reservation\HotelReservation\Domain\ValueObject\Client;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ClientDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $fullName,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Client $entity): static
    {
        return new self(
            $entity->fullName()
        );
    }
}
