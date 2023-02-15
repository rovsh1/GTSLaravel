<?php

namespace GTS\Hotel\Domain\Repository;

use Carbon\CarbonPeriod;

interface RoomQuotaRepositoryInterface
{
    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota);

    public function closeRoomQuota(int $roomId, CarbonPeriod $period);

    public function openRoomQuota(int $roomId, CarbonPeriod $period);
}
