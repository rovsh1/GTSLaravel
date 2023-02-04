<?php

namespace GTS\Reservation\Domain\ValueObject;

use GTS\Reservation\Domain\Entity;

enum ReservationTypeEnum
{
    case GROUP;
    case HOTEL;
    case TRANSFER;
    case AIRPORT;
    case ADDITIONAL;

    public static function fromEntity(Entity\ReservationInterface $reservation): self
    {
        if ($reservation instanceof Entity\ReservationGroup)
            return self::GROUP;
        elseif ($reservation instanceof Entity\HotelReservation)
            return self::HOTEL;
        elseif ($reservation instanceof Entity\TransferReservation)
            return self::TRANSFER;
        elseif ($reservation instanceof Entity\AirportReservation)
            return self::AIRPORT;
        elseif ($reservation instanceof Entity\AdditionalReservation)
            return self::ADDITIONAL;
        else
            throw new \DomainException('Reservation type undefined');
    }
}
