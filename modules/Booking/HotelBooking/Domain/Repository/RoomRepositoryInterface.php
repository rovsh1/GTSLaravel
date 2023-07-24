<?php

namespace Module\Booking\HotelBooking\Domain\Repository;

use Module\Booking\HotelBooking\Domain\Entity\Details\RoomAccommodation;

interface RoomRepositoryInterface
{
    /**
     * @param int $reservationId
     * @return RoomAccommodation[]
     */
    public function getReservationRooms(int $reservationId): array;
}
