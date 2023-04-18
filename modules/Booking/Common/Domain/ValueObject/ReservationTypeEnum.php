<?php

namespace Module\Booking\Common\Domain\ValueObject;

use Module\Booking\Common\Domain\Entity;

enum ReservationTypeEnum: int
{
    case GROUP = 1;
    case HOTEL = 2;
    case TRANSFER = 3;
    case AIRPORT = 4;
    case ADDITIONAL = 5;

    public static function fromEntity(\Module\Booking\Common\Domain\Entity\ReservationInterface $booking): self
    {
        if ($reservation instanceof \Module\Booking\Common\Domain\Entity\ReservationGroup\ReservationGroup)
            return self::GROUP;
        elseif ($reservation instanceof \Module\Booking\Hotel\Domain\Entity\Reservation)
            return self::HOTEL;
        elseif ($reservation instanceof \Module\Booking\Common\Domain\Entity\TransferReservation\TransferReservation)
            return self::TRANSFER;
        elseif ($reservation instanceof \Module\Booking\Common\Domain\Entity\AirportReservation\AirportReservation)
            return self::AIRPORT;
        elseif ($reservation instanceof \Module\Booking\Common\Domain\Entity\AdditionalReservation\AdditionalReservation)
            return self::ADDITIONAL;
        else
            throw new \DomainException('Reservation type undefined');
    }
}
