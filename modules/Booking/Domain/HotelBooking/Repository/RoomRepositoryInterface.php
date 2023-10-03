<?php

namespace Module\Booking\Domain\HotelBooking\Repository;

use Module\Booking\HotelBooking\Domain\Entity\Details\RoomAccommodation;

interface RoomRepositoryInterface
{
    /**
     * @param int $reservationId
     * @return RoomAccommodation[]
     */
    public function getReservationRooms(int $reservationId): array;
}
