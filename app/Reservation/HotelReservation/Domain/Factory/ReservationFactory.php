<?php

namespace GTS\Reservation\HotelReservation\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;

use GTS\Reservation\HotelReservation\Domain\Entity\Reservation;

class ReservationFactory extends AbstractEntityFactory
{
    public static string $entity = Reservation::class;
}
