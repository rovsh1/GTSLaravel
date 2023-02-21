<?php

namespace Module\Hotel\Domain\Adapter;

interface ReservationAdapterInterface
{
    public function getActiveReservationsByHotelId(int $hotelId): array;
}
