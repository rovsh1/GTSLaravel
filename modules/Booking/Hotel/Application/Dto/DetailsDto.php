<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Common\Domain\Entity\Details\HotelDetailsInterface;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class DetailsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $hotelId,
        public readonly BookingPeriodDto $period,
        public readonly ?AdditionalInfoDto $additionalInfo,
        /** @var RoomDto[] $rooms */
        public readonly array $rooms,
        public readonly CancelConditionsDto $cancelConditions
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|HotelDetailsInterface $entity): static
    {
        $additionalInfo = $entity->additionalInfo();
        if ($additionalInfo !== null) {
            $additionalInfo = AdditionalInfoDto::fromDomain($additionalInfo);
        }

        return new static(
            $entity->hotelId(),
            BookingPeriodDto::fromDomain($entity->period()),
            $additionalInfo,
            RoomDto::collectionFromDomain($entity->rooms()->all()),
            CancelConditionsDto::fromDomain($entity->cancelConditions())
        );
    }
}
