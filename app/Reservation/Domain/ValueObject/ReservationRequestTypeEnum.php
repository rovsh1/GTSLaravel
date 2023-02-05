<?php

namespace GTS\Reservation\Domain\ValueObject;

use GTS\Reservation\Domain\Entity;

enum ReservationRequestTypeEnum: int
{
    case RESERVATION = 1;
    case CHANGE = 2;
    case CANCEL = 3;

    public static function fromEntity(Entity\ReservationInterface $reservation): self
    {
        if ($reservation instanceof Entity\ReservationGroup)
            return self::RESERVATION;
        elseif ($reservation instanceof Entity\HotelReservation)
            return self::CHANGE;
        elseif ($reservation instanceof Entity\TransferReservation)
            return self::CANCEL;
        else
            throw new \DomainException('Reservation request type undefined');
    }
}
