<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Common\Domain\Entity\Details\HotelDetailsInterface;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class HotelDetailsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly HotelDto $hotel,
        public readonly BookingPeriodDto $period,
        public readonly AdditionalInfoDto $additionalInfo,
        /** @var RoomDto[] $rooms */
        public readonly array $rooms
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|HotelDetailsInterface $entity): static
    {
        return new static(
            HotelDto::fromDomain($entity->hotel()),
            BookingPeriodDto::fromDomain($entity->period()),
            AdditionalInfoDto::fromDomain($entity->additionalInfo()),
            RoomDto::collectionFromDomain($entity->rooms())
        );
    }
}
