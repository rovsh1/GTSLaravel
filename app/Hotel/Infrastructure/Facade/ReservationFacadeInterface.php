<?php

namespace GTS\Hotel\Infrastructure\Facade;

interface ReservationFacadeInterface
{
    public function getActiveReservations(int $hotelId): array;
}
