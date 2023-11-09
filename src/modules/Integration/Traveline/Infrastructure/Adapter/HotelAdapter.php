<?php

namespace Module\Integration\Traveline\Infrastructure\Adapter;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\UseCase\UpdateQuota;
use Module\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use Module\Shared\Infrastructure\Adapter\AbstractModuleAdapter;

class HotelAdapter extends AbstractModuleAdapter implements HotelAdapterInterface
{
    public function getHotelById(int $hotelId)
    {
        return $this->request('findById', ['id' => $hotelId]);
    }

    public function getRoomsAndRatePlans(int $hotelId)
    {
        return $this->request('getRoomsWithPriceRatesByHotelId', ['id' => $hotelId]);
    }

    public function updateRoomQuota(CarbonPeriod $period, int $roomId, int $quota)
    {
        return app(UpdateQuota::class)->execute($roomId, $period, $quota);
    }

    public function updateRoomPrice(
        CarbonPeriod $period,
        int $roomId,
        int $rateId,
        int $guestsCount,
        bool $isResident,
        string $currencyCode,
        float $price
    ) {
        return $this->request('updateRoomPrice', [
            'room_id' => $roomId,
            'rate_id' => $rateId,
            'guests_count' => $guestsCount,
            'currency_code' => $currencyCode,
            'price' => $price,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
            'is_resident' => $isResident
        ]);
    }

    public function openRoomRate(CarbonPeriod $period, int $roomId, int $rateId)
    {
        return $this->request('openRoomQuota', [
            'room_id' => $roomId,
            'rate_id' => $rateId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }

    public function closeRoomRate(CarbonPeriod $period, int $roomId, int $rateId)
    {
        return $this->request('closeRoomQuota', [
            'room_id' => $roomId,
            'rate_id' => $rateId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }

    protected function getModuleKey(): string
    {
        return 'hotelOld';
    }
}
