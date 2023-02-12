<?php

namespace GTS\Integration\Traveline\Domain\Adapter;

use Carbon\CarbonPeriod;

interface HotelAdapterInterface
{
    public function getHotelById(int $hotelId);

    public function getRoomsAndRatePlans(int $hotelId);

    public function updateRoomQuota(CarbonPeriod $period, int $roomId, int $quota);

    public function updateRoomRatePrice(CarbonPeriod $period, int $roomId, int $rateId, string $currencyCode, float $price);

    public function openRoomRate(CarbonPeriod $period, int $roomId, int $rateId);

    public function closeRoomRate(CarbonPeriod $period, int $roomId, int $rateId);
}
