<?php

namespace Module\Reservation\HotelReservation\Domain\Repository;

use Module\Reservation\HotelReservation\Domain\Entity\Room;

interface RoomRepositoryInterface
{
    /**
     * @param int $reservationId
     * @return Room[]
     */
    public function getReservationRooms(int $reservationId): array;
}
