<?php

namespace GTS\Integration\Traveline\Infrastructure\Adapter;

use Carbon\CarbonPeriod;

use GTS\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Shared\Infrastructure\Adapter\AbstractPortAdapter;

class HotelAdapter extends AbstractPortAdapter implements HotelAdapterInterface
{
    public function getHotelById(int $hotelId)
    {
        return $this->request('hotel/findById', ['id' => $hotelId]);
    }

    public function getRoomsAndRatePlans(int $hotelId)
    {
        return $this->request('hotel/getRoomsWithPriceRatesByHotelId', ['id' => $hotelId]);
    }

    public function updateRoomQuota(CarbonPeriod $period, int $roomId, int $quota)
    {
        return $this->request('reserveQuota', [
            'room_id' => $roomId,
            'date' => $period->getStartDate(),
            'count' => $quota
        ]);
    }

    public function updateRoomRatePrice(CarbonPeriod $period, int $roomId, int $rateId, string $currencyCode, float $price)
    {
        // TODO: Implement updateRoomRatePrice() method.
    }

    public function openRoomRate(CarbonPeriod $period, int $roomId, int $rateId)
    {
        // TODO: Implement openRoomRate() method.
    }

    public function closeRoomRate(CarbonPeriod $period, int $roomId, int $rateId)
    {
        // TODO: Implement closeRoomRate() method.
    }
}
