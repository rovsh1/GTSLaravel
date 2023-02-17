<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Facade;

use GTS\Reservation\HotelReservation\Application\Dto\ReservationDto;

interface InfoFacadeInterface
{
    public function findById(int $id): ReservationDto;

    /**
     * @param int|null $hotelId
     * @return ReservationDto[]
     */
    public function searchActiveReservations(?int $hotelId = null): array;

    public function searchReservations(array $criteria): array;
}
