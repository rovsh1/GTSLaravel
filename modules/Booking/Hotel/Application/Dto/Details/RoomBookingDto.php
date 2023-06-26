<?php

namespace Module\Booking\Hotel\Application\Dto\Details;

use Module\Booking\Hotel\Application\Dto\Details\RoomBooking\GuestDto;
use Module\Booking\Hotel\Application\Dto\Details\RoomBooking\RoomBookingDetailsDto;
use Module\Booking\Hotel\Application\Dto\Details\RoomBooking\RoomInfoDto;
use Module\Booking\Hotel\Application\Dto\Details\RoomBooking\RoomPriceDto;
use Module\Booking\Hotel\Domain\Entity\RoomBooking;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomBookingDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $status,
        public readonly RoomInfoDto $roomInfo,
        /** @var GuestDto[] $guests */
        public readonly array $guests,
        public readonly RoomBookingDetailsDto $details,
        public readonly RoomPriceDto $price
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|RoomBooking $entity): static
    {
        return new static(
            id: $entity->id()->value(),
            status: $entity->status()->value,
            roomInfo: RoomInfoDto::fromDomain($entity->roomInfo()),
            guests: GuestDto::collectionFromDomain($entity->guests()->all()),
            details: RoomBookingDetailsDto::fromDomain($entity->details()),
            price: RoomPriceDto::fromDomain($entity->price())
        );
    }
}
