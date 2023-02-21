<?php

namespace Module\Reservation\Common\Domain\ValueObject;

use GTS\Reservation\Domain\Entity;

enum ReservationTypeEnum: int
{
    case GROUP = 1;
    case HOTEL = 2;
    case TRANSFER = 3;
    case AIRPORT = 4;
    case ADDITIONAL = 5;

    public static function fromEntity(\Module\Reservation\Common\Domain\Entity\ReservationInterface $reservation): self
    {
        if ($reservation instanceof \Module\Reservation\Common\Domain\Entity\ReservationGroup\ReservationGroup)
            return self::GROUP;
        elseif ($reservation instanceof \Module\Reservation\HotelReservation\Domain\Entity\Reservation)
            return self::HOTEL;
        elseif ($reservation instanceof \Module\Reservation\Common\Domain\Entity\TransferReservation\TransferReservation)
            return self::TRANSFER;
        elseif ($reservation instanceof \Module\Reservation\Common\Domain\Entity\AirportReservation\AirportReservation)
            return self::AIRPORT;
        elseif ($reservation instanceof \Module\Reservation\Common\Domain\Entity\AdditionalReservation\AdditionalReservation)
            return self::ADDITIONAL;
        else
            throw new \DomainException('Reservation type undefined');
    }
}
