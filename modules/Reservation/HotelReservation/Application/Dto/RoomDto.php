<?php

namespace Module\Reservation\HotelReservation\Application\Dto;

use Module\Reservation\HotelReservation\Domain\Entity\Room;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int     $id,
        /** @var GuestDto[] $guests */
        public readonly array   $guests,
        public readonly int     $rateId,
        public readonly ?string $checkInTime,
        public readonly ?string $checkOutTime,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Room $room): static
    {
        return new static(
            $room->id(),
            GuestDto::collectionFromDomain($room->guests()),
            $room->rateId(),
            $room->checkInTime(),
            $room->checkOutTime(),
        );
    }
}
