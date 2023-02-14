<?php

namespace GTS\Hotel\Domain\Repository;

use Carbon\CarbonPeriod;

interface RoomQuotaRepositoryInterface
{
    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota);
}
