<?php

namespace GTS\Reservation\Domain\Factory;

use GTS\Reservation\Domain\Entity\ReservationInterface;
use GTS\Reservation\Domain\ValueObject\ReservationTypeEnum;

class HotelReservationFactory
{
    public static function fromType(ReservationTypeEnum $type): ReservationInterface
    {

    }
}
