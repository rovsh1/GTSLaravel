<?php

namespace Module\Hotel\Infrastructure\Adapter;

use Module\Hotel\Domain\Adapter\ReservationAdapterInterface;
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
