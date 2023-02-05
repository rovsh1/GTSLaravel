<?php

namespace GTS\Reservation\HotelReservation\Domain\Factory;

use GTS\Reservation\Common\Domain\Entity\ReservationInterface;
use GTS\Reservation\Common\Domain\ValueObject\ReservationTypeEnum;
use GTS\Reservation\Domain\Factory\Entity;

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
