<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;

interface ReservationFacadeInterface
{
    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota);

    public function openRoomQuota(int $roomId, CarbonPeriod $period, int $rateId);

    public function closeRoomQuota(int $roomId, CarbonPeriod $period, int $rateId);

    public function updateRoomPriceRate(int $roomId, CarbonPeriod $period, int $rateId, string $currencyCode);
}
