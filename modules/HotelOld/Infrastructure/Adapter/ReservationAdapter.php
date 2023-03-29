<?php

namespace Module\HotelOld\Infrastructure\Adapter;

use Module\HotelOld\Domain\Adapter\ReservationAdapterInterface;
use Module\Shared\Infrastructure\Adapter\AbstractPortAdapter;

class ReservationAdapter extends AbstractPortAdapter implements ReservationAdapterInterface
{
    public function getActiveReservationsByHotelId(int $hotelId): array
    {
        return $this->request('hotelReservation/getByHotelId', [
            'hotel_id' => $hotelId
        ]);
    }
}
