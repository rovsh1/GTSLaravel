<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Common\Domain\Entity\Booking;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class BookingDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly BookingStatusDto $status,
        public readonly string $note,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Booking $entity): static
    {
        return new static(
            $entity->id(),
            CityDto::fromDomain($entity->city()),
            ClientDto::fromDomain($entity->client()),
            HotelDetailsDto::fromDomain($entity->hotelDetails()),
            $entity->note()
        );
    }
}
