<?php

namespace Module\Integration\Traveline\Infrastructure\Adapter;

use Carbon\CarbonPeriod;
use Module\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use Module\Shared\Infrastructure\Adapter\AbstractPortAdapter;

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
            'quota' => $quota,
        ]);
    }

    public function updateReleaseDays(CarbonPeriod $period, int $roomId, int $releaseDays): mixed
    {
        return $this->request('hotel/updateReleaseDays', [
            'room_id' => $roomId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
            'release_days' => $releaseDays
        ]);
    }

    public function updateRoomPrice(
        CarbonPeriod $period,
        int $roomId,
        int $rateId,
        int $guestsNumber,
        bool $isResident,
        string $currencyCode,
        float $price
    ) {
        return $this->request('hotel/updateRoomPrice', [
            'room_id' => $roomId,
            'rate_id' => $rateId,
            'guests_number' => $guestsNumber,
            'currency_code' => $currencyCode,
            'price' => $price,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
            'is_resident' => $isResident
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
}
