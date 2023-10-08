<?php

namespace Module\Catalog\Domain\Hotel\Adapter;

interface ReservationAdapterInterface
{
    public function getActiveReservationsByHotelId(int $hotelId): array;
}
