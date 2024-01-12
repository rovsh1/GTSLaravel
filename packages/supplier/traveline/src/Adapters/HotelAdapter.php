<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Adapters;

use Carbon\CarbonPeriod;

class HotelAdapter
{
    public function getHotelById(int $hotelId) {}

    public function getRoomsAndRatePlans(int $hotelId): array {}

    public function updateRoomQuota(CarbonPeriod $period, int $roomId, int $quota) {}

    public function updateReleaseDays(CarbonPeriod $period, int $roomId, int $releaseDays): mixed {}

    public function updateRoomPrice(
        CarbonPeriod $period,
        int $roomId,
        int $rateId,
        int $guestsNumber,
        bool $isResident,
        string $currencyCode,
        float $price
    ) {}

    public function openRoomRate(CarbonPeriod $period, int $roomId, int $rateId) {}

    public function closeRoomRate(CarbonPeriod $period, int $roomId, int $rateId) {}
}
