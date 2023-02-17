<?php

namespace GTS\Integration\Traveline\Domain\Adapter;

use Carbon\CarbonPeriod;

interface HotelAdapterInterface
{
    public function getHotelById(int $hotelId);

    public function getRoomsAndRatePlans(int $hotelId);

    public function updateRoomQuota(CarbonPeriod $period, int $roomId, int $quota);

    public function updateRoomPrice(CarbonPeriod $period, int $roomId, int $rateId, int $guestsNumber, string $currencyCode, float $price, bool $isResident);

    public function openRoomRate(CarbonPeriod $period, int $roomId, int $rateId);

    public function closeRoomRate(CarbonPeriod $period, int $roomId, int $rateId);

    public function getActiveReservations(int $hotelId);
}
