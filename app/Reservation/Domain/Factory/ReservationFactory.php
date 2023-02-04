<?php

namespace GTS\Reservation\Domain\Factory;

use GTS\Reservation\Domain\Entity\ReservationInterface;
use GTS\Reservation\Domain\ValueObject\ReservationTypeEnum;

class ReservationFactory
{
    public static function fromType(ReservationTypeEnum $type): ReservationInterface
    {
        return match ($type) {
            ReservationTypeEnum::GROUP => Entity\ReservationGroup::class,
            ReservationTypeEnum::HOTEL => Entity\HotelReservation::class,
            ReservationTypeEnum::TRANSFER => Entity\TransferReservation::class,
            ReservationTypeEnum::AIRPORT => Entity\AirportReservation::class,
            ReservationTypeEnum::ADDITIONAL => Entity\AdditionalReservation::class,
            default => throw new \DomainException('Reservation type undefined')
        };
    }
}
