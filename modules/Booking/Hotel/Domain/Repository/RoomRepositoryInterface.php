<?php

namespace Module\Booking\Hotel\Domain\Repository;

use Module\Booking\Hotel\Domain\Entity\Room;

interface RoomRepositoryInterface
{
    /**
     * @param int $reservationId
     * @return Room[]
     */
    public function getReservationRooms(int $reservationId): array;
}
