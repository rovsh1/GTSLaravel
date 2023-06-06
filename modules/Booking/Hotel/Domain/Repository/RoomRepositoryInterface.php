<?php

namespace Module\Booking\Hotel\Domain\Repository;

use Module\Booking\Hotel\Domain\Entity\Details\RoomAccommodation;

interface RoomRepositoryInterface
{
    /**
     * @param int $reservationId
     * @return RoomAccommodation[]
     */
    public function getReservationRooms(int $reservationId): array;
}
