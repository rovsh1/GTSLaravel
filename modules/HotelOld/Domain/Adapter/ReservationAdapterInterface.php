<?php

namespace Module\HotelOld\Domain\Adapter;

interface ReservationAdapterInterface
{
    public function getActiveReservationsByHotelId(int $hotelId): array;
}
