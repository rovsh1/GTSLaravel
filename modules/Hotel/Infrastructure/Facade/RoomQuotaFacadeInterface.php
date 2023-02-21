<?php

namespace Module\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;

interface RoomQuotaFacadeInterface
{
    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota);

    public function openRoomQuota(int $roomId, CarbonPeriod $period, int $rateId);

    public function closeRoomQuota(int $roomId, CarbonPeriod $period, int $rateId);
}
