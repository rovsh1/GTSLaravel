<?php

namespace GTS\Reservation\Common\Domain\ValueObject;

use GTS\Reservation\Domain\Entity;

enum ReservationRequestTypeEnum: int
{
    case RESERVATION = 1;
    case CHANGE = 2;
    case CANCEL = 3;

    public static function fromEntity(\GTS\Reservation\Common\Domain\Entity\ReservationInterface $reservation): self
    {
        if ($reservation instanceof \GTS\Reservation\Common\Domain\Entity\ReservationGroup\ReservationGroup)
            return self::RESERVATION;
        elseif ($reservation instanceof \GTS\Reservation\HotelReservation\Domain\Entity\Reservation)
            return self::CHANGE;
        elseif ($reservation instanceof \GTS\Reservation\Common\Domain\Entity\TransferReservation\TransferReservation)
            return self::CANCEL;
        else
            throw new \DomainException('Reservation request type undefined');
    }
}
