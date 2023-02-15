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
        return $this->request('hotel/updateRoomQuota', [
            'room_id' => $roomId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
            'quota' => $quota
        ]);
    }

    public function updateRoomPrice(CarbonPeriod $period, int $roomId, int $rateId, string $currencyCode, float $price)
    {
        return $this->request('hotel/updateRoomPrice', [
            'room_id' => $roomId,
            'rate_id' => $rateId,
            'currency_code' => $currencyCode,
            'price' => $price,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }

    public function openRoomRate(CarbonPeriod $period, int $roomId, int $rateId)
    {
        return $this->request('hotel/openRoomQuota', [
            'room_id' => $roomId,
            'rate_id' => $rateId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }

    public function closeRoomRate(CarbonPeriod $period, int $roomId, int $rateId)
    {
        return $this->request('hotel/closeRoomQuota', [
            'room_id' => $roomId,
            'rate_id' => $rateId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }

    public function getActiveReservations(int $hotelId): array
    {
        return $this->request('hotel/getActiveReservations', [
            'hotel_id' => $hotelId,
        ]);
    }
}
