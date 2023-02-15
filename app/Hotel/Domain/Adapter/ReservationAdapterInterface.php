<?php

namespace GTS\Hotel\Domain\Adapter;

interface ReservationAdapterInterface
{
    public function getActiveReservationsByHotelId(int $hotelId): array;
}
