<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Hotel\Application\Dto\Details\AdditionalInfoDto;
use Module\Booking\Hotel\Application\Dto\Details\BookingPeriodDto;
use Module\Booking\Hotel\Application\Dto\Details\CancelConditionsDto;
use Module\Booking\Hotel\Application\Dto\Details\HotelInfoDto;
use Module\Booking\Hotel\Application\Dto\Details\RoomBookingDto;
use Module\Booking\Hotel\Domain\ValueObject\Details;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class DetailsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly HotelInfoDto $hotelInfo,
        public readonly BookingPeriodDto $period,
        public readonly ?AdditionalInfoDto $additionalInfo,
        /** @var RoomBookingDto[] $rooms */
        public readonly array $roomBookings,
        public readonly CancelConditionsDto $cancelConditions
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Details $entity): static
    {
        return new static(
            HotelInfoDto::fromDomain($entity->hotelInfo()),
            BookingPeriodDto::fromDomain($entity->period()),
            $entity->additionalInfo() !== null ? AdditionalInfoDto::fromDomain($entity->additionalInfo()) : null,
            RoomBookingDto::collectionFromDomain($entity->roomBookings()->all()),
            CancelConditionsDto::fromDomain($entity->cancelConditions())
        );
    }
}
