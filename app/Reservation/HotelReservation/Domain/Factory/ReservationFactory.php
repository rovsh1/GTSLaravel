<?php

namespace GTS\Reservation\HotelReservation\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;

use GTS\Reservation\HotelReservation\Domain\Entity\Reservation;
use GTS\Reservation\HotelReservation\Domain\ValueObject;

class ReservationFactory extends AbstractEntityFactory
{
    public static string $entity = Reservation::class;

    public function __construct(
        public readonly ValueObject\ReservationIdentifier $identifier,
        //private readonly Manager $author,
        public readonly ValueObject\ReservationStatus     $status,
        public readonly ValueObject\Client                $client,
        public readonly ValueObject\Hotel                 $hotel,
        public readonly ValueObject\ReservationPeriod     $reservationPeriod,
        public readonly ValueObject\ReservationDetails    $details,
        //private readonly ValueObject\Price $price,
    ) {}
}
