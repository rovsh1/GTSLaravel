<?php

namespace Module\Booking\Domain\Shared\ValueObject;

/**
 * @deprecated 
 */
enum BookingTypeEnum: int
{
    case HOTEL = 1;
    case TRANSFER = 2;
    case AIRPORT = 3;
    case ADDITIONAL = 4;

    public static function fromEntity(\Module\Booking\Domain\Shared\Entity\BookingInterface $booking): self
    {
        if ($booking instanceof \Module\Booking\Deprecated\HotelBooking\HotelBooking)
            return self::HOTEL;
        elseif ($booking instanceof \Module\Booking\Domain\Entity\TransferReservation\Transfer)
            return self::TRANSFER;
        elseif ($booking instanceof \Module\Booking\Domain\Entity\AirportReservation\Airport)
            return self::AIRPORT;
        elseif ($booking instanceof \Module\Booking\Domain\Entity\AdditionalReservation\AdditionalReservation)
            return self::ADDITIONAL;
        else
            throw new \DomainException('Reservation type undefined');
    }
}
