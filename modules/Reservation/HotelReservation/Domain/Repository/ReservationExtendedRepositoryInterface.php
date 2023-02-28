<?php

namespace Module\Reservation\HotelReservation\Domain\Repository;

use Carbon\CarbonInterface;
use Module\Reservation\HotelReservation\Domain\Entity\Reservation;

interface ReservationExtendedRepositoryInterface
{
    public function find(int $id): ?Reservation;

    /**
     * @param CarbonInterface $dateUpdate
     * @param int|null $hotelId
     * @return Reservation[]
     */
    public function searchByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array;

    /**
     * @param int|null $hotelId
     * @return Reservation[]
     */
    public function searchActive(?int $hotelId): array;
}
