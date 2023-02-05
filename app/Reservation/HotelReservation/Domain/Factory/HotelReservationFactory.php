<?php

namespace GTS\Reservation\HotelReservation\Domain\Factory;

use GTS\Reservation\Common\Domain\Entity\ReservationInterface;
use GTS\Reservation\Common\Domain\ValueObject\ReservationTypeEnum;

class HotelReservationFactory
{
    public static function fromType(ReservationTypeEnum $type): ReservationInterface
    {

    }
}
