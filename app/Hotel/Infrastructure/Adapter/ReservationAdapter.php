<?php

namespace GTS\Hotel\Infrastructure\Adapter;

use GTS\Hotel\Domain\Adapter\ReservationAdapterInterface;
use GTS\Shared\Infrastructure\Adapter\AbstractPortAdapter;

class ReservationAdapter extends AbstractPortAdapter implements ReservationAdapterInterface
{
    public function getActiveReservationsByHotelId(int $hotelId): array
    {
        return $this->request('hotelReservation/getByHotelId', [
            'hotel_id' => $hotelId
        ]);
    }
}
