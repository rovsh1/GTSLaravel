<?php

namespace Module\Reservation\HotelReservation\Application\Dto;

use Module\Reservation\HotelReservation\Domain\Entity\Reservation;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ReservationDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int      $id,
        public readonly HotelDto $hotelInfo,
        /** @var RoomDto[]|null $rooms */
        public readonly ?array   $rooms = null
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Reservation $reservation): static
    {
        $rooms = $reservation->rooms() !== null
            ? RoomDto::collectionFromDomain($reservation->rooms())
            : null;

        return new static(
            $reservation->id(),
            HotelDto::fromDomain($reservation->hotel()),
            $rooms
        );
    }
}
