<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Facade;

use GTS\Reservation\HotelReservation\Application\Dto\ReservationDto;

interface InfoFacadeInterface
{
    public function findById(int $id): ReservationDto;
}
