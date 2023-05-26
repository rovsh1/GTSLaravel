<?php

namespace Module\Booking\Common\Domain\ValueObject;


enum BookingTypeEnum: int
{
    case HOTEL = 1;
    case TRANSFER = 2;
    case AIRPORT = 3;
    case ADDITIONAL = 4;

    public static function fromEntity(\Module\Booking\Common\Domain\Entity\ReservationInterface $booking): self
    {
        if ($booking instanceof \Module\Booking\Hotel\Domain\Entity\Booking)
            return self::HOTEL;
        elseif ($booking instanceof \Module\Booking\Common\Domain\Entity\TransferReservation\TransferReservation)
            return self::TRANSFER;
        elseif ($booking instanceof \Module\Booking\Common\Domain\Entity\AirportReservation\AirportReservation)
            return self::AIRPORT;
        elseif ($booking instanceof \Module\Booking\Common\Domain\Entity\AdditionalReservation\AdditionalReservation)
            return self::ADDITIONAL;
        else
            throw new \DomainException('Reservation type undefined');
    }
}
