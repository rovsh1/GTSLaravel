<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Facade;

use Carbon\CarbonInterface;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;

interface InfoFacadeInterface
{
    public function findById(int $id): ReservationDto;

    /**
     * @param int|null $hotelId
     * @return ReservationDto[]
     */
    public function searchActiveReservations(?int $hotelId = null): array;

    /**
     * @param CarbonInterface $dateUpdate
     * @param int|null $hotelId
     * @return ReservationDto[]
     */
    public function searchReservationsByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array;
}
